<?php

namespace App\Policies;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArsipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Arsip $arsip): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Arsip $arsip): bool
    {
        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            return false;
        }

        // Admin atau uploader bisa edit
        return $user->role === 'admin' || $user->id === $arsip->uploader_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Arsip $arsip): bool
    {
        // Hanya admin yang bisa hapus
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Arsip $arsip): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Arsip $arsip): bool
    {
        return false;
    }
}
