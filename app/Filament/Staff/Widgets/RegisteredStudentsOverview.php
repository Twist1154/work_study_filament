<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegisteredStudentsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Get the authenticated staff member and their assigned department
        $staff = auth()->user()?->staffMember;
        $departmentId = $staff?->department_id;

        // 2. Fetch global registration metrics
        $totalRegistrations = Registration::count();
        $pendingVerifications = Registration::where('status', 'pending_verification')->count();

        // 3. Fetch registrations scoped strictly to this staff member's department (via Invitation relationship)
        $deptRegistrations = $departmentId
            ? Registration::whereHas('invitation', fn ($query) => $query->where('department_id', $departmentId))->count()
            : 0;

        $stats = [
            Stat::make('Total Registrations', $totalRegistrations)
                ->description('All registrations across all campuses')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Awaiting Verification', $pendingVerifications)
                ->description('Pending review by Dean\'s Assistant')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];

        // 4. Dynamically append a card for their department if the relationship is set
        if ($staff && $departmentId) {
            $stats[] = Stat::make('Department Registrations', $deptRegistrations)
                ->description('Registrations submitted to your department')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success');
        }

        return $stats;
    }
}
