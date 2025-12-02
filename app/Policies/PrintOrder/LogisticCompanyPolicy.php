<?php

declare(strict_types=1);

namespace App\Policies\PrintOrder;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PrintOrder\LogisticCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogisticCompanyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LogisticCompany');
    }

    public function view(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('View:LogisticCompany');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LogisticCompany');
    }

    public function update(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('Update:LogisticCompany');
    }

    public function delete(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('Delete:LogisticCompany');
    }

    public function restore(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('Restore:LogisticCompany');
    }

    public function forceDelete(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('ForceDelete:LogisticCompany');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LogisticCompany');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LogisticCompany');
    }

    public function replicate(AuthUser $authUser, LogisticCompany $logisticCompany): bool
    {
        return $authUser->can('Replicate:LogisticCompany');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LogisticCompany');
    }

}