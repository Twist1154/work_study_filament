<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
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
    protected $primaryKey = 'student_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'user_id',
        'student_number',
        'surname',
        'first_names',
        'gender',
        'date_of_birth',
        'id_passport_number',
        'sars_tax_number',
        'is_foreign_student',
        'work_permit_number',
        'work_permit_expiry',
        'fee_account_outstanding',
        'nsfas_funded',
        'full_bursary_holder',
        'bursary_settled_before_sem2',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'student_id' => 'integer',
            'user_id' => 'integer',
            'date_of_birth' => 'date',
            'is_foreign_student' => 'boolean',
            'work_permit_expiry' => 'date',
            'fee_account_outstanding' => 'boolean',
            'nsfas_funded' => 'boolean',
            'full_bursary_holder' => 'boolean',
            'bursary_settled_before_sem2' => 'boolean',
        ];
    }

    /**
     * Relationship to the parent User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship to the Student's registered Addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'student_id', 'student_id');
    }

    /**
     * Relationship to the Student's Bank Details.
     */
    public function bankDetail(): HasOne
    {
        return $this->hasOne(BankDetail::class, 'student_id', 'student_id');
}

    /**
     * Relationship to the Student's Workstudy Terms Agreements.
     */
    public function workstudyTerms(): HasMany
    {
        return $this->hasMany(WorkstudyTerm::class, 'student_id', 'student_id');
    }

    /**
     * Relationship to the Student's Tax Declarations.
     */
    public function taxDeclarations(): HasMany
    {
        return $this->hasMany(TaxDeclaration::class, 'student_id', 'student_id');
    }
}
