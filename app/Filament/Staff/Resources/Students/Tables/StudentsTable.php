<?php

namespace App\Filament\Staff\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('student_number')
                    ->searchable(),
                TextColumn::make('surname')
                    ->searchable(),
                TextColumn::make('first_names')
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable(),
                /* TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('id_passport_number')
                    ->searchable(),
               TextColumn::make('sars_tax_number')
                    ->searchable(),*/
                IconColumn::make('is_foreign_student')
                    ->boolean(),
                /*TextColumn::make('work_permit_number')
                    ->searchable(),
                TextColumn::make('work_permit_expiry')
                    ->date()
                    ->sortable(),*/
                IconColumn::make('fee_account_outstanding')
                    ->boolean(),
                IconColumn::make('nsfas_funded')
                    ->boolean(),
                IconColumn::make('full_bursary_holder')
                    ->boolean(),
                IconColumn::make('bursary_settled_before_sem2')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
