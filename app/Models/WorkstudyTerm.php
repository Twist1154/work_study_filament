<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkstudyTerm extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $primaryKey = 'terms_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'terms_id',
        'student_id',
        'supervisor_id',
        'student_signature_file',
        'student_signed_date',
        'student_signed_place',
        'supervisor_signature_file',
        'supervisor_signed_date',
        'supervisor_signed_place',
        'terms_accepted',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'terms_id' => 'integer',
            'student_id' => 'integer',
            'supervisor_id' => 'integer',
            'student_signed_date' => 'date',
            'supervisor_signed_date' => 'date',
            'terms_accepted' => 'boolean',
        ];
    }


    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'terms_id', 'registration_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function staffMember(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'supervisor_id', 'staff_id');
    }
}
