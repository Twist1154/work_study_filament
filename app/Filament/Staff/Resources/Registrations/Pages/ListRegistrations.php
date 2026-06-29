<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Registrations\Pages;

use App\Filament\Staff\Resources\Registrations\RegistrationResource;
use App\Models\Registration;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Onboarding'),

            'pending' => Tab::make('Pending Verification')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_verification'))
                ->badge(Registration::where('status', 'pending_verification')->count())
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
