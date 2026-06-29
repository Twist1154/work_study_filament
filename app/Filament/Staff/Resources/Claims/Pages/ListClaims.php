<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Claims\Pages; // UPDATED to match your folder path [1.1]

use App\Filament\Staff\Resources\Claims\ClaimResource;
use App\Models\Claim;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListClaims extends ListRecords
{
    protected static string $resource = ClaimResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Claims'),

            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'submitted'))
                ->badge(Claim::where('status', 'submitted')->count())
                ->badgeColor('gray'),

            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['supervisor_approved', 'coordinator_approved']))
                ->badge(Claim::whereIn('status', ['supervisor_approved', 'coordinator_approved'])->count())
                ->badgeColor('warning'),

            'paid' => Tab::make('Paid')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid'))
                ->badge(Claim::where('status', 'paid')->count())
                ->badgeColor('success'),
        ];
    }
}
