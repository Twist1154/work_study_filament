<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'job_category_id',
        'department_id',
        'campus_id',
        'supervisor_id',
        'first_names',
        'surname',
        'cost_centre',
        'expires_at',
        'status',
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
        ];
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
