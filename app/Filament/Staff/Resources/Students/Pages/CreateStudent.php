<?php

namespace App\Filament\Staff\Resources\Students\Pages;

use App\Filament\Staff\Resources\Students\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
