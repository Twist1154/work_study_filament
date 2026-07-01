<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Invitations\Schemas;

use Filament\Schemas\Components\Section; // FIXED: Updated for Filament 5
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student Biographical Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('CPUT Student Email Address')
                            ->email()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('first_names')
                            ->label('First Name(s)')
                            ->string()
                            ->maxLength(200)
                            ->nullable(),

                        TextInput::make('surname')
                            ->label('Surname')
                            ->string()
                            ->maxLength(100)
                            ->nullable(),
                    ]),

                Section::make('Workstudy Appointment Pre-Configuration')
                    ->columns(2)
                    ->schema([
                        Select::make('job_category_id')
                            ->relationship('jobCategory', 'category_name')
                            ->label('Job Category')
                            ->required(),

                        Select::make('department_id')
                            ->relationship('department', 'department_name')
                            ->label('Department')
                            ->required(),

                        Select::make('campus_id')
                            ->relationship('campus', 'campus_name')
                            ->label('Campus')
                            ->required(),

                        Select::make('supervisor_id')
                            ->relationship('staffMember', 'full_name') // Binds to original 'staffMember' relationship
                            ->label('Department Supervisor')
                            ->required(),

                        TextInput::make('cost_centre')
                            ->label('Cost Centre')
                            ->default('Y269')
                            ->required()
                            ->maxLength(20),
                    ]),
            ]);
    }
}
