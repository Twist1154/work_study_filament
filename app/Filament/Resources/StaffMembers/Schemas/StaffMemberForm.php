<?php

namespace App\Filament\Resources\StaffMembers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StaffMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('staff_number'),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('role')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'id'),
            ]);
    }
}
