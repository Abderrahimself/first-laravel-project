<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // You can implement this if needed, otherwise return true or false
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Implement this if needed, otherwise return true or false
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Implement this if needed, otherwise return true or false
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Check if the user is an admin
        if ($user->isAdmin) {
            return true;
        }

        // Check if the user is the owner of the post
        return $user->id === $post->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Check if the user is an admin
        if ($user->isAdmin) {
            return true;
        }

        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        // You can implement this if needed, otherwise return true or false
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        // You can implement this if needed, otherwise return true or false
        return true;
    }
}
