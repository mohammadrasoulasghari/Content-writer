<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\RequestLog;
use App\Models\User;

class RequestLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any RequestLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RequestLog $requestlog): bool
    {
        return $user->checkPermissionTo('view RequestLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create RequestLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RequestLog $requestlog): bool
    {
        return $user->checkPermissionTo('update RequestLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RequestLog $requestlog): bool
    {
        return $user->checkPermissionTo('delete RequestLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RequestLog $requestlog): bool
    {
        return $user->checkPermissionTo('restore RequestLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RequestLog $requestlog): bool
    {
        return $user->checkPermissionTo('force-delete RequestLog');
    }
}
