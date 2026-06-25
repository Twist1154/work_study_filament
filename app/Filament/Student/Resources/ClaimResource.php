<?php

namespace App\Filament\Student\Resources\Claims;

use App\Filament\Student\Resources\Claims\Pages\CreateClaim;
use App\Filament\Student\Resources\Claims\Pages\EditClaim;
use App\Filament\Student\Resources\Claims\Pages\ListClaims;
use App\Filament\Student\Resources\Claims\Schemas\ClaimForm;
use App\Filament\Student\Resources\Claims\Tables\ClaimsTable;
use App\Models\Claim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Claims';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Filament 5 form schema definition...

                // You can hide the student_id field completely and set it in the creation hook,
                // or pass it as a hidden field with a default value:
                \Filament\Forms\Components\Hidden::make('student_id')
                    ->default(fn () => auth()->user()->student?->student_id),

                // Collect other fields safely:
                \Filament\Forms\Components\Select::make('claim_month')
                    ->options(array_combine(range(1, 12), array_map(fn($m) => date('F', mktime(0,0,0,$m,10)), range(1, 12))))
                    ->required(),

                \Filament\Forms\Components\TextInput::make('hours_worked')
                    ->numeric()
                    ->required(),
            ])
            ->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return ClaimsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClaims::route('/'),
            'create' => CreateClaim::route('/create'),
            'edit' => EditClaim::route('/{record}/edit'),
        ];
    }
}
