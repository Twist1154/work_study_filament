<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxDeclaration extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'declaration_id',
        'student_id',
        'works_less_than_22hrs',
        'no_other_employer',
        'declaration_text',
        'signed_place',
        'declaration_date',
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
            'declaration_id' => 'integer',
            'student_id' => 'integer',
            'works_less_than_22hrs' => 'boolean',
            'no_other_employer' => 'boolean',
            'declaration_date' => 'date',
            'tax_rate_applied' => 'decimal:4',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
