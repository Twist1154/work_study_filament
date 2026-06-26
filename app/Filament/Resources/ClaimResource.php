<?php
// app/Filament/Resources/ClaimResource.php.bak

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ClaimResource\Pages;
use App\Models\Claim;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

// Filament 5 Schema core import
use Illuminate\Database\Eloquent\Builder;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'My Claims Sheet';

    /**
     * CRITICAL SECURITY: Scopes every query on this resource to the logged-in student.
     * Prevents students from reading or modifying other users' claims.
     */
    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()->student;

        return parent::getEloquentQuery()
            ->where('student_id', $student ? $student->student_id : 0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('claim_year')
                    ->label('Year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('claim_month')
                    ->label('Month')
                    ->formatStateUsing(fn(int $state): string => date('F', mktime(0, 0, 0, $state, 10)))
                    ->sortable(),

                Tables\Columns\TextColumn::make('hours_worked')
                    ->label('Hours Worked')
                    ->numeric(2),

                Tables\Columns\TextColumn::make('amount_claimed')
                    ->label('Total Claimed')
                    ->money('ZAR'), // Adjust to your local currency

                Tables\Columns\TextColumn::make('amount_to_bank')
                    ->label('Paid to Bank')
                    ->money('ZAR'),

                Tables\Columns\TextColumn::make('amount_to_fees')
                    ->label('Paid to Fees')
                    ->money('ZAR'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'supervisor_approved' => 'info',
                        'coordinator_approved' => 'warning',
                        'paid' => 'success',
                        default => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // Adjust which standard CRUD pages the student can access
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClaims::route('/'),
            'create' => Pages\CreateClaim::route('/create'),
        ];
    }
}
