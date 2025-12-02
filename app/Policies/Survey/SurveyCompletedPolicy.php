<?php

declare(strict_types=1);

namespace App\Policies\Survey;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Survey\SurveyCompleted;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyCompletedPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SurveyCompleted');
    }

    public function view(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('View:SurveyCompleted');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SurveyCompleted');
    }

    public function update(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('Update:SurveyCompleted');
    }

    public function delete(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('Delete:SurveyCompleted');
    }

    public function restore(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('Restore:SurveyCompleted');
    }

    public function forceDelete(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('ForceDelete:SurveyCompleted');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SurveyCompleted');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SurveyCompleted');
    }

    public function replicate(AuthUser $authUser, SurveyCompleted $surveyCompleted): bool
    {
        return $authUser->can('Replicate:SurveyCompleted');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SurveyCompleted');
    }

}