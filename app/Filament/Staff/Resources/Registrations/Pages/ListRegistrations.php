<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Registrations\Pages;

use App\Filament\Staff\Resources\RegistrationResource;
use App\Models\Registration;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    /**
     * ADDED: Dynamic navigation filter tabs for the onboarding queues [1.1.2, 1.2.3]
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All in Progress'),

            'pending_verification' => Tab::make('Pending Verification')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending_student', 'pending_verification']))
                ->badge(Registration::whereIn('status', ['pending_student', 'pending_verification'])->count())
                ->badgeColor('gray'),

            'pending_hod' => Tab::make('HOD Sign-Off')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_hod_approval'))
                ->badge(Registration::where('status', 'pending_hod_approval')->count())
                ->badgeColor('warning'),

            'final' => Tab::make('Pending Final Approval')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_final'))
                ->badge(Registration::where('status', 'pending_final')->count())
                ->badgeColor('info'),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(Registration::where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}
