<?php

namespace App\Filament\Staff\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class StaffDashboard extends BaseDashboard
{
    protected static ?string $title = 'Staff Dashboard';

    /**
     * TODO: ADD IMAGES FOR THE DASHBOARD background
     * Injects inline CSS directly onto the <body> tag of this page
     */
    public function getExtraBodyAttributes(): array
    {
        return [
            'style' => "background-image: url('" . asset('images/staff-bg.jpg') . "'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;",
        ];
}
}
