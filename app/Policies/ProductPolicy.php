<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
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

    public function view(User $user, Product $product): bool {
        return $user->isCashier();
    }

    public function create(User $user): bool {
        return $user->isCashier();
    }

    public function update(User $user, Product $product): bool {
        return $user->isCashier();
    }

    public function delete(User $user, Product $product): bool {
        return $user->isCashier();
    }

    public function restore(User $user, Product $product): bool {
        return $user->isCashier();
    }

    public function forceDelete(User $user, Product $product): bool {
        return $user->isCashier();
    }
}
