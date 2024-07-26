<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AiModel;
use App\Models\User;

class AiModelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AiModel');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AiModel $aimodel): bool
    {
        return $user->checkPermissionTo('view AiModel');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AiModel');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AiModel $aimodel): bool
    {
        return $user->checkPermissionTo('update AiModel');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AiModel $aimodel): bool
    {
        return $user->checkPermissionTo('delete AiModel');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AiModel $aimodel): bool
    {
        return $user->checkPermissionTo('restore AiModel');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AiModel $aimodel): bool
    {
        return $user->checkPermissionTo('force-delete AiModel');
    }
}
