<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'registration_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'registration_id',
        'invitation_id',
        'student_id',
        'status',
        'conditions_accepted',
        'verifier_id',
        'hod_approver_id',
        'final_approver_id',
        'hod_signature_file',
        'hod_signature_date',
        'hod_signature_place',
        'claims_sheet_pdf_path',
        'verification_status', // FIXED: Added to allow saving verification comments [1.1.2]
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_id' => 'integer',
            'invitation_id' => 'integer',
            'student_id' => 'integer',
            'conditions_accepted' => 'boolean',
            'verifier_id' => 'integer',
            'hod_approver_id' => 'integer',
            'final_approver_id' => 'integer',
            'hod_signature_date' => 'date',
            'created_at' => 'datetime',
            'verification_status' => 'array', // FIXED: Safely casts JSON to PHP array [1.1.2]
        ];
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class, 'invitation_id', 'invitation_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'verifier_id', 'staff_id');
    }

    public function hodApprover(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'hod_approver_id', 'staff_id');
    }

    public function finalApprover(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'final_approver_id', 'staff_id');
    }
}
