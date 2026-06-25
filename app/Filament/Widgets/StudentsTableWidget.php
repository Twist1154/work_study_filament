<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StudentsTableWidget extends BaseWidget
{
    // Optional: Sets the display order of the widget on your dashboard
    protected static ?int $sort = 1;

    // Optional: Forces the widget to span the full width of the grid
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
            // Pulls data from your Student model
                Student::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('student_number')
                    ->searchable()
                    ->sortable()
                    ->label('Student Number'),

                Tables\Columns\TextColumn::make('first_names')
                    ->searchable()
                    ->sortable()
                    ->label('First Name(s)'),

                Tables\Columns\IconColumn::make('nsfas_funded')
                    ->boolean()
                    ->label('NSFAS Funded'),
            ])
            ->defaultSort('student_number', 'asc');
    }
}
