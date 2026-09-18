<?php

declare(strict_types=1);

namespace App\Policies\Work;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Work\WorkComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkCommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WorkComment');
    }

    public function view(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('View:WorkComment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WorkComment');
    }

    public function update(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('Update:WorkComment');
    }

    public function delete(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('Delete:WorkComment');
    }

    public function restore(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('Restore:WorkComment');
    }

    public function forceDelete(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('ForceDelete:WorkComment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WorkComment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WorkComment');
    }

    public function replicate(AuthUser $authUser, WorkComment $workComment): bool
    {
        return $authUser->can('Replicate:WorkComment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WorkComment');
    }

}
