<?php

namespace App\Filament\Staff\Resources\Invitations;

use App\Filament\Staff\Resources\Invitations\Pages\CreateInvitation;
use App\Filament\Staff\Resources\Invitations\Pages\EditInvitation;
use App\Filament\Staff\Resources\Invitations\Pages\ListInvitations;
use App\Filament\Staff\Resources\Invitations\Schemas\InvitationForm;
use App\Filament\Staff\Resources\Invitations\Tables\InvitationsTable;
use App\Models\Invitation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // ADDED: Import Builder for Eloquent queries

class InvitationResource extends Resource
{
    protected static ?string $model = Invitation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?string $navigationLabel = 'Student Invitations';

    /**
     * SECURITY SCOPE: Restricts HOD Assistants / Staff so they can only see
     * and manage invitations generated within their specific department.
     */
    public static function getEloquentQuery(): Builder
    {
        $staff = auth()->user()?->staffMember;
        $departmentId = $staff?->department_id;

        if ($staff && $departmentId) {
            return parent::getEloquentQuery()
                ->where('department_id', $departmentId);
        }

        return parent::getEloquentQuery();
    }

    public static function form(Schema $schema): Schema
    {
        return InvitationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvitationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvitations::route('/'),
            'create' => CreateInvitation::route('/create'),
            'edit' => EditInvitation::route('/{record}/edit'),
        ];
    }
}
