<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ClaimResource\Pages;
use App\Models\Claim;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'My Claims';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\Hidden::make('student_id')
                    ->default(fn () => auth()->user()->student?->student_id),

                Forms\Components\Select::make('claim_month')
                    ->options(array_combine(range(1, 12), array_map(fn($m) => date('F', mktime(0,0,0,$m,10)), range(1, 12))))
                    ->required(),

                Forms\Components\TextInput::make('hours_worked')
                    ->numeric()
                    ->required(),
            ]);
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

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()->student;

        return parent::getEloquentQuery()
            ->where('student_id', $student ? $student->student_id : 0);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClaims::route('/'),
            'create' => Pages\CreateClaim::route('/create'),
            'edit' => Pages\EditClaim::route('/{record}/edit'),
        ];
    }
}
