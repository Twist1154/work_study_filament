<?php

declare(strict_types=1);

namespace App\Filament\Student\Pages;

use App\Models\Student;
use App\Models\Address;
use App\Models\BankDetail;
use App\Models\WorkstudyTerm;
use App\Models\TaxDeclaration;
use App\Models\Document;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads; // FIXED: Imported Livewire File Uploads trait [1.1.2]

class RegisterStudent extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads; // FIXED: Used File Uploads trait inside the class [1.1.2]

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.student.pages.register-student';

    /**
     * Binds form fields to the $data array in state.
     *
     * @var array<string, mixed>
     */
    public array $data = [];

    // FIXED: Consolidated the duplicate mount() methods here
    public function mount(): void
    {
        // If the authenticated user already has a student profile,
        // redirect them to their main dashboard immediately.
        if (auth()->user()->student()->exists()) {
            $this->redirect('/student');
            return;
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    // ==========================================
                    // STEP 1: APPOINTMENT & BIOGRAPHICAL DETAILS
                    // ==========================================
                    Step::make('Biographical Details')
                        ->schema([
                            Section::make('Appointment Details')
                                ->columns(2)
                                ->schema([
                                    Select::make('appointment_type')
                                        ->label('Appointment Type')
                                        ->options([
                                            'New Appointment' => 'New Appointment',
                                            'Renewal' => 'Renewal of Previous Appointment',
                                        ])
                                        ->required(),

                                    TextInput::make('student_number')
                                        ->label('Student Number')
                                        ->string()
                                        ->maxLength(50)
                                        ->required(),

                                    TextInput::make('staff_number')
                                        ->label('Staff Number (If Applicable)')
                                        ->string()
                                        ->maxLength(50)
                                        ->nullable(),

                                    TextInput::make('sars_tax_number')
                                        ->label('SARS Tax Number')
                                        ->string()
                                        ->maxLength(20)
                                        ->nullable(),
                                ]),

                            Section::make('Personal Information')
                                ->columns(3)
                                ->schema([
                                    Select::make('title')
                                        ->options([
                                            'Mr' => 'Mr',
                                            'Ms' => 'Ms',
                                            'Dr' => 'Dr',
                                            'Prof' => 'Prof',
                                        ])
                                        ->required(),

                                    TextInput::make('first_names')
                                        ->label('First Name(s)')
                                        ->string()
                                        ->maxLength(200)
                                        ->required(),

                                    TextInput::make('surname')
                                        ->label('Surname')
                                        ->string()
                                        ->maxLength(100)
                                        ->required(),

                                    TextInput::make('initials')
                                        ->label('Initial(s)')
                                        ->string()
                                        ->maxLength(10)
                                        ->nullable(),

                                    Select::make('gender')
                                        ->label('Gender')
                                        ->options([
                                            'Male' => 'Male',
                                            'Female' => 'Female',
                                        ])
                                        ->required(),

                                    Select::make('marital_status')
                                        ->options([
                                            'Single' => 'Single',
                                            'Married' => 'Married',
                                            'Divorced' => 'Divorced',
                                            'Widowed' => 'Widowed',
                                        ])
                                        ->nullable(),

                                    TextInput::make('nationality')
                                        ->string()
                                        ->maxLength(100)
                                        ->required(),

                                    DatePicker::make('date_of_birth')
                                        ->maxDate(now())
                                        ->required(),

                                    TextInput::make('id_passport_number')
                                        ->label('Identity / Passport Number')
                                        ->string()
                                        ->maxLength(50)
                                        ->required(),

                                    Toggle::make('is_foreign_student')
                                        ->live()
                                        ->label('Are you a foreign student?')
                                        ->columnSpanFull(),

                                    TextInput::make('work_permit_number')
                                        ->string()
                                        ->maxLength(50)
                                        ->visible(fn (callable $get): bool => (bool) $get('is_foreign_student'))
                                        ->required(fn (callable $get): bool => (bool) $get('is_foreign_student')),

                                    DatePicker::make('work_permit_expiry')
                                        ->minDate(now())
                                        ->visible(fn (callable $get): bool => (bool) $get('is_foreign_student'))
                                        ->required(fn (callable $get): bool => (bool) $get('is_foreign_student')),
                                ]),

                            Section::make('Home Address')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('home_street_number')->integer()->required(),
                                    TextInput::make('home_street_name')->string()->maxLength(150)->required(),
                                    TextInput::make('home_suburb')->string()->maxLength(100)->required(),
                                    TextInput::make('home_city')->string()->maxLength(100)->required(),
                                    TextInput::make('home_post_code')->string()->maxLength(20)->required(),
                                ]),

                            Section::make('Current Address')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('current_street_number')->integer()->required(),
                                    TextInput::make('current_street_name')->string()->maxLength(150)->required(),
                                    TextInput::make('current_suburb')->string()->maxLength(100)->required(),
                                    TextInput::make('current_city')->string()->maxLength(100)->required(),
                                    TextInput::make('current_post_code')->string()->maxLength(20)->required(),
                                ]),

                            Section::make('Contact Details')
                                ->schema([
                                    TextInput::make('contact_number')
                                        ->tel()
                                        ->required(),
                                ]),
                        ]),

                    // ==========================================
                    // STEP 2: BANK DETAIL INFORMATION
                    // ==========================================
                    Step::make('Bank Details')
                        ->schema([
                            // Section A: Employee Details (Display Reactive Previews from Step 1)
                            Section::make('Section A - Employee Details')
                                ->columns(2)
                                ->schema([
                                    Placeholder::make('employee_surname')
                                        ->label('Surname')
                                        ->content(fn (callable $get): string => (string) ($get('surname') ?? 'N/A')),

                                    Placeholder::make('employee_first_names')
                                        ->label('First Name(s)')
                                        ->content(fn (callable $get): string => (string) ($get('first_names') ?? 'N/A')),

                                    Placeholder::make('employee_identity_number')
                                        ->label('Identity / Passport Number')
                                        ->content(fn (callable $get): string => (string) ($get('id_passport_number') ?? 'N/A')),

                                    Placeholder::make('employee_tax_number')
                                        ->label('Tax Reference Number')
                                        ->content(fn (callable $get): string => (string) ($get('sars_tax_number') ?? 'N/A')),
                                ]),

                            // Section B: Bank Account Details
                            Section::make('Section B - Bank Account Details')
                                ->columns(2)
                                ->schema([
                                    Select::make('account_type')
                                        ->options([
                                            'Current' => 'Current / Cheque Account',
                                            'Savings' => 'Savings Account',
                                            'Transmission' => 'Transmission Account',
                                        ])
                                        ->required(),

                                    TextInput::make('account_number')
                                        ->string()
                                        ->maxLength(30)
                                        ->required(),

                                    TextInput::make('bank_name')
                                        ->string()
                                        ->maxLength(100)
                                        ->required(),

                                    TextInput::make('branch_name')
                                        ->string()
                                        ->maxLength(100)
                                        ->nullable(),

                                    TextInput::make('branch_code')
                                        ->string()
                                        ->maxLength(20)
                                        ->nullable(),

                                    Select::make('ownership_type')
                                        ->options([
                                            'own' => 'Own Account',
                                            'joint' => 'Joint Account',
                                            'third_party' => 'Third Party Account',
                                        ])
                                        ->live()
                                        ->required()
                                        ->columnSpanFull(),
                                ]),

                            // Section C: Third-Party Account Details (Shows conditionally)
                            Section::make('Section C - Third Party Account Details')
                                ->visible(fn (callable $get): bool => in_array($get('ownership_type'), ['joint', 'third_party']))
                                ->columns(2)
                                ->schema([
                                    TextInput::make('third_party_name')
                                        ->label('Account Holder Name')
                                        ->required()
                                        ->string()
                                        ->maxLength(200),

                                    TextInput::make('third_party_relationship')
                                        ->label('Relationship to Account Holder')
                                        ->required()
                                        ->string()
                                        ->maxLength(100),
                                ]),

                            // Section D: Declaration
                            Section::make('Section D - Declaration')
                                ->schema([
                                    Toggle::make('bank_declaration_accepted')
                                        ->label('I certify that the banking details provided above are correct.')
                                        ->required(),
                                ]),
                        ]),

                    // ==========================================
                    // STEP 3: SUPPORTING DOCUMENTS (UPLOAD)
                    // ==========================================
                    Step::make('Uploads')
                        ->schema([
                            Section::make('Supporting Documents Checklist')
                                ->description('Ensure your uploaded documents are certified where necessary.')
                                ->schema([
                                    FileUpload::make('id_copy')
                                        ->label('Certified Copy of ID / Passport')
                                        ->disk('public') // Changed to public disk for standard Filament URL generation [1.1.2]
                                        ->directory('registrations/ids')
                                        ->required(),

                                    FileUpload::make('proof_of_registration')
                                        ->label('Proof of Student Registration')
                                        ->disk('public')
                                        ->directory('registrations/proofs')
                                        ->required(),

                                    FileUpload::make('bank_account_proof')
                                        ->label('Proof of Bank Account (Bank statement / letter)')
                                        ->disk('public')
                                        ->directory('registrations/banks')
                                        ->required(),

                                    FileUpload::make('tax_certificate')
                                        ->label('Tax Number Confirmation (Optional / If available)')
                                        ->disk('public')
                                        ->directory('registrations/tax')
                                        ->nullable(),

                                    FileUpload::make('permit_copy')
                                        ->label('Work / Study Permit')
                                        ->disk('public')
                                        ->directory('registrations/permits')
                                        ->visible(fn (callable $get): bool => (bool) $get('is_foreign_student'))
                                        ->required(fn (callable $get): bool => (bool) $get('is_foreign_student')),
                                ]),
                        ]),

                    // ==========================================
                    // STEP 4: TERMS & CONDITIONS
                    // ==========================================
                    Step::make('Terms & Conditions')
                        ->schema([
                            Section::make('Program regulations')
                                ->description('Please review and acknowledge the Terms and Conditions')
                                ->schema([
                                    Placeholder::make('regulations_text')
                                        ->hiddenLabel()
                                        ->content(new HtmlString('
                                            <ul class="list-disc pl-5 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                                <li>I have read and understood the Workstudy Programme regulations.</li>
                                                <li>I agree to comply with all programme requirements.</li>
                                                <li>I understand the payment and claim submission rules.</li>
                                                <li>I understand the working hour limitations.</li>
                                                <li>I understand disciplinary procedures and consequences.</li>
                                                <li>I understand the regulations applicable to foreign students.</li>
                                            </ul>
                                        ')),
                                ]),

                            Section::make('Signatures')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('terms_signed_place')
                                        ->label('Signed at CPUT (Location)')
                                        ->required()
                                        ->string()
                                        ->maxLength(200),

                                    DatePicker::make('terms_signed_date')
                                        ->label('Date Signed')
                                        ->default(now())
                                        ->required(),

                                    FileUpload::make('student_signature_file')
                                        ->label('Upload Electronic Signature')
                                        ->disk('public')
                                        ->directory('signatures/students')
                                        ->required(),

                                    Toggle::make('terms_accepted')
                                        ->label('I accept the Workstudy offer of employment & service conditions.')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ==========================================
                    // STEP 5: TAX DECLARATION
                    // ==========================================
                    Step::make('Tax Declaration')
                        ->schema([
                            Section::make('Declaration')
                                ->schema([
                                    Toggle::make('works_less_than_22hrs')
                                        ->label('I declare that I work fewer than 22 hours per completed week.')
                                        ->default(true)
                                        ->required(),

                                    Toggle::make('no_other_employer')
                                        ->label('I declare that I have no other employer.')
                                        ->default(true)
                                        ->required(),

                                    Textarea::make('declaration_text')
                                        ->label('Declaration Statement')
                                        ->placeholder('Write your custom statement or declarations in your own words...')
                                        ->rows(5)
                                        ->nullable(),

                                    TextInput::make('tax_signed_place')
                                        ->label('Signed at (Place)')
                                        ->required()
                                        ->string()
                                        ->maxLength(200),

                                    DatePicker::make('tax_signed_date')
                                        ->label('Date of Declaration')
                                        ->default(now())
                                        ->required(),
                                ]),
                        ]),
                ])
                ->submitAction(new HtmlString(
                    '<button type="submit" class="fi-btn fi-btn-color-primary fi-size-md relative inline-grid grid-flow-col items-center justify-center gap-1.5 rounded-lg border font-semibold outline-none transition duration-75 focus-visible:ring-2 shadow-sm px-3.5 py-2 text-sm bg-primary-600 text-white hover:bg-primary-500 border-transparent dark:bg-primary-500 dark:hover:bg-primary-400">Submit Registration</button>'
                ))
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        // Enforces full validation checks on current steps
        $formData = $this->form->getState();

        DB::transaction(function () use ($formData) {
            $userId = auth()->id();

            // 1. Save Student model
            $student = Student::create([
                'user_id' => $userId,
                'student_number' => $formData['student_number'] ?? null,
                'surname' => $formData['surname'],
                'first_names' => $formData['first_names'],
                'gender' => $formData['gender'],
                'date_of_birth' => $formData['date_of_birth'],
                'id_passport_number' => $formData['id_passport_number'],
                'sars_tax_number' => $formData['sars_tax_number'] ?? null,
                'is_foreign_student' => (bool) ($formData['is_foreign_student'] ?? false),
                'work_permit_number' => $formData['is_foreign_student'] ? ($formData['work_permit_number'] ?? null) : null,
                'work_permit_expiry' => $formData['is_foreign_student'] ? ($formData['work_permit_expiry'] ?? null) : null,

                // Inherited system parameters defaults
                'fee_account_outstanding' => true,
                'nsfas_funded' => false,
                'full_bursary_holder' => false,
                'bursary_settled_before_sem2' => false,
            ]);

            // 2. Save Home Address
            Address::create([
                'student_id' => $student->student_id,
                'street_number' => (int) $formData['home_street_number'],
                'street_name' => $formData['home_street_name'],
                'suburb' => $formData['home_suburb'],
                'city' => $formData['home_city'],
                'post_code' => $formData['home_post_code'],
            ]);

            // 3. Save Current Address
            Address::create([
                'student_id' => $student->student_id,
                'street_number' => (int) $formData['current_street_number'],
                'street_name' => $formData['current_street_name'],
                'suburb' => $formData['current_suburb'],
                'city' => $formData['current_city'],
                'post_code' => $formData['current_post_code'],
            ]);

            // 4. Save Banking Details
            BankDetail::create([
                'student_id' => $student->student_id,
                'account_type' => $formData['account_type'],
                'account_number' => $formData['account_number'],
                'bank_name' => $formData['bank_name'],
                'branch_name' => $formData['branch_name'] ?? null,
                'branch_code' => $formData['branch_code'] ?? null,
                'ownership_type' => $formData['ownership_type'],
                'third_party_name' => in_array($formData['ownership_type'], ['joint', 'third_party']) ? ($formData['third_party_name'] ?? null) : null,
                'third_party_relationship' => in_array($formData['ownership_type'], ['joint', 'third_party']) ? ($formData['third_party_relationship'] ?? null) : null,
                'valid_from' => now()->toDateString(),
            ]);

            // 5. Save Terms Agreement
            WorkstudyTerm::create([
                'student_id' => $student->student_id,
                'student_signature_file' => $formData['student_signature_file'],
                'student_signed_date' => $formData['terms_signed_date'],
                'student_signed_place' => $formData['terms_signed_place'],
                'terms_accepted' => (bool) $formData['terms_accepted'],
            ]);

            // 6. Save Tax Declaration
            TaxDeclaration::create([
                'student_id' => $student->student_id,
                'works_less_than_22hrs' => (bool) $formData['works_less_than_22hrs'],
                'no_other_employer' => (bool) $formData['no_other_employer'],
                'declaration_text' => $formData['declaration_text'] ?? null,
                'signed_place' => $formData['tax_signed_place'],
                'declaration_date' => $formData['tax_signed_date'],
                'tax_rate_applied' => 0.0,
            ]);

            // 7. Save Supporting Uploads into the "documents" table
            // ID / Passport
            if (!empty($formData['id_copy'])) {
                Document::create([
                    'student_id' => $student->student_id,
                    'document_type' => 'ID Copy',
                    'file_path' => $formData['id_copy'],
                    'uploaded_at' => now(),
                ]);
            }

            // Proof of Student Registration
            if (!empty($formData['proof_of_registration'])) {
                Document::create([
                    'student_id' => $student->student_id,
                    'document_type' => 'Proof of Registration',
                    'file_path' => $formData['proof_of_registration'],
                    'uploaded_at' => now(),
                ]);
            }

            // Proof of Bank Account (Saved as 'Other' to align with enum)
            if (!empty($formData['bank_account_proof'])) {
                Document::create([
                    'student_id' => $student->student_id,
                    'document_type' => 'Other',
                    'file_path' => $formData['bank_account_proof'],
                    'uploaded_at' => now(),
                ]);
            }

            // Tax Number Confirmation
            if (!empty($formData['tax_certificate'])) {
                Document::create([
                    'student_id' => $student->student_id,
                    'document_type' => 'SARS Tax Certificate',
                    'file_path' => $formData['tax_certificate'],
                    'uploaded_at' => now(),
                ]);
            }

            // Study Permit / Visa
            if ($formData['is_foreign_student'] && !empty($formData['permit_copy'])) {
                Document::create([
                    'student_id' => $student->student_id,
                    'document_type' => 'Study Permit',
                    'file_path' => $formData['permit_copy'],
                    'permit_expiry_date' => $formData['work_permit_expiry'] ?? null,
                    'uploaded_at' => now(),
                ]);
            }
        });

        Notification::make()
            ->title('Registration submitted successfully!')
            ->success()
            ->send();

        $this->redirect('/student');
    }
    }
