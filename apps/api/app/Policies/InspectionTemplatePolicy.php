<?php

namespace App\Policies;

use App\Models\AppUser;
use App\Models\InspectionTemplate;
use Illuminate\Auth\Access\Response;

class InspectionTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AppUser $user): bool
    {
        return $user->hasPermissionTo('inspection:create') || $user->hasPermissionTo('inspection:execute') || $user->hasPermissionTo('inspection:view_scope');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AppUser $user, InspectionTemplate $inspectionTemplate): bool
    {
        if ($user->tenant_id !== $inspectionTemplate->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('inspection:create') || $user->hasPermissionTo('inspection:execute') || $user->hasPermissionTo('inspection:view_scope');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(AppUser $user): bool
    {
        return $user->hasPermissionTo('inspection:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(AppUser $user, InspectionTemplate $inspectionTemplate): bool
    {
        if ($user->tenant_id !== $inspectionTemplate->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('inspection:create');
    }
}
