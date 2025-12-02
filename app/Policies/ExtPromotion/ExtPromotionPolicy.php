<?php

declare(strict_types=1);

namespace App\Policies\ExtPromotion;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ExtPromotion\ExtPromotion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtPromotionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExtPromotion');
    }

    public function view(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('View:ExtPromotion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExtPromotion');
    }

    public function update(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('Update:ExtPromotion');
    }

    public function delete(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('Delete:ExtPromotion');
    }

    public function restore(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('Restore:ExtPromotion');
    }

    public function forceDelete(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('ForceDelete:ExtPromotion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExtPromotion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExtPromotion');
    }

    public function replicate(AuthUser $authUser, ExtPromotion $extPromotion): bool
    {
        return $authUser->can('Replicate:ExtPromotion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExtPromotion');
    }

}