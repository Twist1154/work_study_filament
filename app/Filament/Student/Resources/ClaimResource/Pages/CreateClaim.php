<?php

namespace App\Filament\Student\Resources\ClaimResource\Pages;

use App\Filament\Student\Resources\ClaimResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClaim extends CreateRecord
{
    protected static string $resource = ClaimResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = auth()->user()->student->student_id;
        $data['claim_year'] = date('Y');
        // Status is usually 'submitted' by default in migrations, but let's be explicit if needed
        $data['status'] = 'submitted';

        return $data;
    }
}
