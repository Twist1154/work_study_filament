<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $primaryKey = 'claim_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'claim_id',
        'appointment_id',
        'student_id',
        'claim_month',
        'claim_year',
        'hours_worked',
        'amount_claimed',
        'amount_to_fees',
        'amount_to_bank',
        'approved_by_id',
        'status',
        'is_late_claim',
        'locked_after_supervisor_approval',
        'tax_rate_applied',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'claim_id' => 'integer',
            'appointment_id' => 'integer',
            'student_id' => 'integer',
            'hours_worked' => 'decimal:2',
            'amount_claimed' => 'decimal:2',
            'amount_to_fees' => 'decimal:2',
            'amount_to_bank' => 'decimal:2',
            'approved_by_id' => 'integer',
            'is_late_claim' => 'boolean',
            'locked_after_supervisor_approval' => 'boolean',
            'tax_rate_applied' => 'decimal:4',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function staffMember(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'approved_by_id', 'staff_id');
    }
}
