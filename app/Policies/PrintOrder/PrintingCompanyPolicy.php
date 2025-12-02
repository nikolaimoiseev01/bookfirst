<?php

declare(strict_types=1);

namespace App\Policies\PrintOrder;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PrintOrder\PrintingCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintingCompanyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PrintingCompany');
    }

    public function view(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('View:PrintingCompany');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PrintingCompany');
    }

    public function update(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('Update:PrintingCompany');
    }

    public function delete(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('Delete:PrintingCompany');
    }

    public function restore(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('Restore:PrintingCompany');
    }

    public function forceDelete(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('ForceDelete:PrintingCompany');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PrintingCompany');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PrintingCompany');
    }

    public function replicate(AuthUser $authUser, PrintingCompany $printingCompany): bool
    {
        return $authUser->can('Replicate:PrintingCompany');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PrintingCompany');
    }

}