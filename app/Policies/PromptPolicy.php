<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Prompt;
use App\Models\User;

class PromptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Prompt');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prompt $prompt): bool
    {
        return $user->checkPermissionTo('view Prompt');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Prompt');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prompt $prompt): bool
    {
        return $user->checkPermissionTo('update Prompt');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prompt $prompt): bool
    {
        return $user->checkPermissionTo('delete Prompt');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prompt $prompt): bool
    {
        return $user->checkPermissionTo('restore Prompt');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prompt $prompt): bool
    {
        return $user->checkPermissionTo('force-delete Prompt');
    }
}
