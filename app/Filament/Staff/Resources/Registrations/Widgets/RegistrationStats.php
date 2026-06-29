<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Registrations\Widgets;

use App\Models\Registration;
use App\Models\Invitation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Count registrations currently requiring coordinator verification
        $pendingVerificationCount = Registration::query()
            ->whereIn('status', ['pending_student', 'pending_verification'])
            ->count();

        // 2. Count registrations currently awaiting HOD signature
        $pendingHodCount = Registration::query()
            ->where('status', 'pending_hod_approval')
            ->count();

        // 3. Count email links/invitations that have not been opened yet.
        // NOTE: If your 'invitations' table uses a boolean column like 'is_opened' instead of 'opened_at',
        // you can change this to: ->where('is_opened', false)
        $unopenedEmailsCount = Invitation::query()
            ->whereNull('opened_at')
            ->count();

        return [
            Stat::make('Awaiting Verification', $pendingVerificationCount)
                ->description('Needs initial review')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color('warning'),

            Stat::make('Awaiting HOD Approval', $pendingHodCount)
                ->description('Pending department signature')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Unopened Invitation Links', $unopenedEmailsCount)
                ->description('Email links not yet clicked')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('danger'),
        ];
    }
}
