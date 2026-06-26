<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkLog extends Model
{
    protected $primaryKey = 'work_log_id';

    protected $fillable = [
        'work_log_id',
        'student_id',
        'appointment_id',
        'clock_in_at',
        'clock_out_at',
        'hours_worked',
        'lunch_break_minutes',
    ];

    protected function casts(): array
    {
        return [
            'work_log_id' => 'integer',
            'student_id' => 'integer',
            'appointment_id' => 'integer',
            'clock_in_at' => 'datetime',
            'clock_out_at' => 'datetime',
            'hours_worked' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }
}
