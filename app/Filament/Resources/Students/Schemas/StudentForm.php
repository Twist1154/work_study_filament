<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('student_number'),
                TextInput::make('surname')
                    ->required(),
                TextInput::make('first_names')
                    ->required(),
                TextInput::make('gender'),
                DatePicker::make('date_of_birth'),
                TextInput::make('id_passport_number'),
                TextInput::make('sars_tax_number'),
                Toggle::make('is_foreign_student')
                    ->required(),
                TextInput::make('work_permit_number'),
                DatePicker::make('work_permit_expiry'),
                Toggle::make('fee_account_outstanding')
                    ->required(),
                Toggle::make('nsfas_funded')
                    ->required(),
                Toggle::make('full_bursary_holder')
                    ->required(),
                Toggle::make('bursary_settled_before_sem2')
                    ->required(),
            ]);
    }
}
