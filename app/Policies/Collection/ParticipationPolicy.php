<?php

declare(strict_types=1);

namespace App\Policies\Collection;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Collection\Participation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParticipationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Participation');
    }

    public function view(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('View:Participation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Participation');
    }

    public function update(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('Update:Participation');
    }

    public function delete(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('Delete:Participation');
    }

    public function restore(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('Restore:Participation');
    }

    public function forceDelete(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('ForceDelete:Participation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Participation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Participation');
    }

    public function replicate(AuthUser $authUser, Participation $participation): bool
    {
        return $authUser->can('Replicate:Participation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Participation');
    }

}