<?php

declare(strict_types=1);

namespace App\Policies\OwnBook;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OwnBook\OwnBook;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnBookPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OwnBook');
    }

    public function view(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('View:OwnBook');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OwnBook');
    }

    public function update(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('Update:OwnBook');
    }

    public function delete(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('Delete:OwnBook');
    }

    public function restore(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('Restore:OwnBook');
    }

    public function forceDelete(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('ForceDelete:OwnBook');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OwnBook');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OwnBook');
    }

    public function replicate(AuthUser $authUser, OwnBook $ownBook): bool
    {
        return $authUser->can('Replicate:OwnBook');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OwnBook');
    }

}