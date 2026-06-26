<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Claims;

use App\Models\Claim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Filament\Staff\Resources\Claims\Pages;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|BackedEnum|null  $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Workstudy Claims';

    protected static ?string $pluralModelLabel = 'Workstudy Claims';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_number')
                    ->label('Student Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.surname')
                    ->label('Surname')
                    ->searchable(),

                Tables\Columns\TextColumn::make('claim_month')
                    ->label('Month')
                    ->formatStateUsing(fn ($state) => date("F", mktime(0, 0, 0, (int) $state, 10))),

                Tables\Columns\TextColumn::make('hours_worked')
                    ->label('Hours')
                    ->numeric(2),

                Tables\Columns\TextColumn::make('amount_claimed')
                    ->label('Amount Claimed')
                    ->money('ZAR'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'submitted' => 'gray',
                        'supervisor_approved' => 'warning',
                        'coordinator_approved' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state))),
            ])
            ->actions([
                Action::make('supervisorApprove')
                    ->label('Supervisor Approve')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->action(fn ($record) => $record->update([
                        'status' => 'supervisor_approved',
                        'locked_after_supervisor_approval' => true,
                    ]))
                    ->color('warning'),

                Action::make('coordinatorApprove')
                    ->label('Coordinator Approve')
                    ->visible(fn ($record) => $record->status === 'supervisor_approved')
                    ->action(fn ($record) => $record->update(['status' => 'coordinator_approved']))
                    ->color('success'),
            ])
            ->bulkActions([
                BulkAction::make('export_payroll')
                    ->label('Export Selected for Payroll (CSV)')
                    ->action(function (Collection $records) {
                        $csvData = [];

                        $csvData[] = ['Student Number', 'Surname', 'First Names', 'Bank', 'Account Number', 'Net Hours', 'Rate/Hr', 'Fee Allocation', 'Bank Allocation', 'Month', 'Year'];

                        foreach ($records as $claim) {
                            $rate = $claim->appointment->remuneration_rate_per_hour ?? 50.00;
                            $csvData[] = [
                                $claim->student->student_number ?? 'N/A',
                                $claim->student->surname ?? 'N/A',
                                $claim->student->first_names ?? 'N/A',
                                $claim->student->bankDetail->bank_name ?? 'N/A',
                                $claim->student->bankDetail->account_number ?? 'N/A',
                                $claim->hours_worked,
                                $rate,
                                $claim->amount_to_fees,
                                $claim->amount_to_bank,
                                $claim->claim_month,
                                $claim->claim_year,
        ];
    }

                        return response()->streamDownload(function () use ($csvData) {
                            $file = fopen('php://output', 'w');
                            foreach ($csvData as $row) {
                                fputcsv($file, $row);
                            }
                            fclose($file);
                        }, 'payroll-export-' . now()->format('Y-m-d') . '.csv');
                    })
                    ->deselectRecordsAfterCompletion()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages::ListClaims::route('/'),
        ];
    }
}
