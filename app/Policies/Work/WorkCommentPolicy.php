<?php

declare(strict_types=1);

namespace App\Policies\Work;

use App\Models\Work\WorkComment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

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
}
