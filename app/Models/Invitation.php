<?php

namespace App\Models;

use App\Mail\StudentInvitationMail; // ADDED: Import the Invitation Mail
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail; // ADDED: Import Mail facade

class Invitation extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $primaryKey = 'invitation_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invitation_id',
        'invitation_token',
        'email', // ADDED: Mass-assignable student email
        'job_category_id',
        'department_id',
        'campus_id',
        'supervisor_id',
        'first_names',
        'surname',
        'cost_centre',
        'expires_at',
        'status',
        'opened_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'invitation_id' => 'integer',
            'job_category_id' => 'integer',
            'department_id' => 'integer',
            'campus_id' => 'integer',
            'supervisor_id' => 'integer',
            'expires_at' => 'datetime',
            'opened_at' => 'datetime',
        ];
    }

    /**
     * Bootstrap the model and its event hooks.
     */
    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation) {
            // Generate a secure 64-character token [1]
            $invitation->invitation_token = bin2hex(random_bytes(32));
            $invitation->expires_at = now()->addHours(48);
            $invitation->status = 'sent';
        });

        static::created(function (Invitation $invitation) {
            // Automatically send the activation mail upon database creation [1]
            Mail::to($invitation->email)->send(new StudentInvitationMail($invitation));
        });
    }

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id', 'job_category_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    public function staffMember(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'supervisor_id', 'staff_id');
    }
}
