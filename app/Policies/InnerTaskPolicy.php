<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InnerTask;
use Illuminate\Auth\Access\HandlesAuthorization;

class InnerTaskPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InnerTask');
    }

    public function view(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('View:InnerTask');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InnerTask');
    }

    public function update(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('Update:InnerTask');
    }

    public function delete(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('Delete:InnerTask');
    }

    public function restore(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('Restore:InnerTask');
    }

    public function forceDelete(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('ForceDelete:InnerTask');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InnerTask');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InnerTask');
    }

    public function replicate(AuthUser $authUser, InnerTask $innerTask): bool
    {
        return $authUser->can('Replicate:InnerTask');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InnerTask');
    }

}