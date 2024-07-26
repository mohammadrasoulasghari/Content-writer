<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\WritingStep;
use App\Models\User;

class WritingStepPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WritingStep');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WritingStep $writingstep): bool
    {
        return $user->checkPermissionTo('view WritingStep');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WritingStep');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WritingStep $writingstep): bool
    {
        return $user->checkPermissionTo('update WritingStep');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WritingStep $writingstep): bool
    {
        return $user->checkPermissionTo('delete WritingStep');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WritingStep $writingstep): bool
    {
        return $user->checkPermissionTo('restore WritingStep');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WritingStep $writingstep): bool
    {
        return $user->checkPermissionTo('force-delete WritingStep');
    }
}
