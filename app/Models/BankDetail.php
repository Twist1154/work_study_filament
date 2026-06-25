<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankDetail extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    // FIXED: Explicitly set the primary key to bank_detail_id [1.1.2]
    protected $primaryKey = 'bank_detail_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_detail_id',
        'student_id',
        'account_type',
        'account_number',
        'bank_name',
        'branch_name',
        'branch_code',
        'ownership_type',
        'third_party_name',
        'third_party_relationship',
        'valid_from',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // FIXED: Removed redundant id cast
            'bank_detail_id' => 'integer',
            'student_id' => 'integer',
            'valid_from' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
