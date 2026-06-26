<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\ClaimResource\Pages;

use App\Filament\Staff\Resources\ClaimResource;
use App\Models\Claim;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListClaims extends ListRecords
{
    protected static string $resource = ClaimResource::class;

    /**
     * ADDED: Dynamic navigation filter tabs at the top of the claims list [1.1.2, 1.2.3]
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Claims'),

            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'submitted'))
                ->badge(Claim::where('status', 'submitted')->count()) // Shows pending count
                ->badgeColor('gray'),

            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['supervisor_approved', 'coordinator_approved']))
                ->badge(Claim::whereIn('status', ['supervisor_approved', 'coordinator_approved'])->count()) // Shows approved count [1.2.3]
                ->badgeColor('warning'),

            'paid' => Tab::make('Paid')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid'))
                ->badge(Claim::where('status', 'paid')->count())
                ->badgeColor('success'),
        ];
}
}
