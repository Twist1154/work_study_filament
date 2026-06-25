<?php
// app/Filament/Student/Resources/ClaimResource/Pages/CreateClaim.php

namespace App\Filament\Student\Resources\ClaimResource\Pages;

use App\Filament\Student\Resources\ClaimResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClaim extends CreateRecord
{
protected static string $resource = ClaimResource::class;

protected function mutateFormDataBeforeCreate(array $data): array
{
$data['student_id'] = auth()->user()->student->student_id;

return $data;
}
}
