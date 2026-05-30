<?php

namespace App\Policies;

use App\Models\AppUser;
use App\Models\Inspection;
use Illuminate\Auth\Access\Response;

class InspectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AppUser $user): bool
    {
        return $user->hasPermissionTo('inspection:view_scope') || $user->hasPermissionTo('inspection:execute');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AppUser $user, Inspection $inspection): bool
    {
        if ($user->tenant_id !== $inspection->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('inspection:view_scope') || $user->hasPermissionTo('inspection:execute');
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
    public function update(AppUser $user, Inspection $inspection): bool
    {
        if ($user->tenant_id !== $inspection->tenant_id) {
            return false;
        }

        return $user->hasPermissionTo('inspection:execute');
    }
}
