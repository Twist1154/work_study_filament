<?php

namespace App\Filament\Staff\Resources\Registrations;

use App\Filament\Staff\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Staff\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Staff\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Staff\Resources\Registrations\Schemas\RegistrationForm;
use App\Filament\Staff\Resources\Registrations\Tables\RegistrationsTable;
use App\Models\Registration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'registration_id';

    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationsTable::configure($table);
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
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'edit' => EditRegistration::route('/{record}/edit'),
        ];
    }
}
