<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function before(User $user, string $ability): bool|null {
        if ($user->isAdmin() || $user->isOwner()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool {
        return $user->isCashier();
    }

    public function view(User $user, Category $category): bool {
        return $user->isCashier();
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, Category $category): bool {
        return false;
    }

    public function delete(User $user, Category $category): bool {
        return false;
    }

    public function restore(User $user, Category $category): bool {
        return false;
    }

    public function forceDelete(User $user, Category $category): bool {
        return false;
    }
}
