<?php

namespace App\Filament\Resources;

use App\Models\Registration;
use App\Models\Appointment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema; // FIXED: Imported Schema class
use Filament\Infolists\Components\TextEntry; // FIXED: Imported Infolist entries
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section; // FIXED: Imported Section from Schemas
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.surname')
                    ->label('Surname')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending_hod_approval' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                // ADDED: Opens a read-only modal displaying the infolist detailed below [1.1.5]
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Review Student Onboarding Package')
                    ->modalWidth('5xl'),

                // Action 1: Verification (Dean's Assistant)
                Action::make('verify')
                    ->visible(fn ($record) => $record->status === 'pending_student')
                    ->action(fn ($record) => $record->update(['status' => 'pending_hod_approval']))
                    ->color('warning'),

                // Action 2: HOD Countersign
                Action::make('hodSign')
                    ->visible(fn ($record) => $record->status === 'pending_hod_approval')
                    ->form([
                        Toggle::make('agree_to_terms')
                            ->label('I agree to the Terms & Conditions on behalf of the department.')
                            ->required(),
                    ])
                    ->action(fn ($record) => $record->update(['status' => 'pending_final']))
                    ->color('success'),

                // Action 3: Final Approval (Dean's Assistant) & Appointment generation
                Action::make('finalApprove')
                    ->visible(fn ($record) => $record->status === 'pending_final')
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            // 1. Approve registration
                            $record->update(['status' => 'approved']);

                            // 2. Generate Appointment automatically from details
                            Appointment::create([
                                'student_id' => $record->student_id,
                                'registration_id' => $record->registration_id,
                                'status' => 'active',
                                // Additional fields from the parent invitations/records can be mapped here
                            ]);
                        });
                    })
                    ->color('success'),
            ]);
    }

    /**
     * FIXED: Method accepts and returns a Schema object in Filament 5.
     */
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([ // FIXED: Register components using components() instead of schema() on the root
                Section::make('Student Biographical Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('student.student_number')->label('Student Number'),
                        TextEntry::make('student.first_names')->label('First Name(s)'),
                        TextEntry::make('student.surname')->label('Surname'),
                        TextEntry::make('student.gender')->label('Gender'),
                        TextEntry::make('student.date_of_birth')->label('Date of Birth')->date(),
                        TextEntry::make('student.id_passport_number')->label('ID / Passport Number'),
                        TextEntry::make('student.sars_tax_number')->label('SARS Tax Number')->placeholder('None Provided'),
                        IconEntry::make('student.is_foreign_student')->label('Foreign Student?')->boolean(),
                        TextEntry::make('student.work_permit_number')
                            ->label('Work Permit Number')
                            ->placeholder('N/A'),
                        TextEntry::make('student.work_permit_expiry')
                            ->label('Work Permit Expiry')
                            ->date()
                            ->placeholder('N/A'),
                    ]),

                Section::make('Banking Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('student.bankDetail.bank_name')->label('Bank'),
                        TextEntry::make('student.bankDetail.account_type')->label('Account Type'),
                        TextEntry::make('student.bankDetail.account_number')->label('Account Number'),
                        TextEntry::make('student.bankDetail.branch_name')->label('Branch Name')->placeholder('N/A'),
                        TextEntry::make('student.bankDetail.branch_code')->label('Branch Code')->placeholder('N/A'),
                        TextEntry::make('student.bankDetail.ownership_type')->label('Account Ownership'),
                    ]),

                Section::make('Uploaded Supporting Documents')
                    ->schema([
                        // Repeats to display every document row in the database associated with this student
                        RepeatableEntry::make('student.documents')
                            ->label('Document Library')
                            ->schema([
                                TextEntry::make('document_type')->label('Document Type'),

                                // Generates a secure view/download URL.TODO
                                // Note: If files are uploaded to 'local' (private) storage, you must configure a private
                                // download route. If you want simple URL download links, ensure your file fields in
                                // RegisterStudent.php use ->disk('public') and you have run php artisan storage:link.
                                TextEntry::make('file_path')
                                    ->label('Action')
                                    ->formatStateUsing(fn () => 'View / Download Attachment')
                                    ->url(fn ($record) => asset('storage/' . $record->file_path), shouldOpenInNewTab: true)
                                    ->color('primary'),

                                TextEntry::make('uploaded_at')->label('Uploaded At')->datetime(),
                            ])
                            ->columns(3)
                    ])
            ]);
    }
}
