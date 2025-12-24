<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function before(User $user, string $ability): bool|null {
        if ($user->is_admin || $user->is_owner) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool {
        return $user->is_cashier;
    }

    public function view(User $user, Product $product): bool {
        return $user->is_cashier;
    }

    public function create(User $user): bool {
        return $user->is_cashier;
    }

    public function update(User $user, Product $product): bool {
        return $user->is_cashier;
    }

    public function delete(User $user, Product $product): bool {
        return $user->is_cashier;
    }

    public function restore(User $user, Product $product): bool {
        return $user->is_cashier;
    }

    public function forceDelete(User $user, Product $product): bool {
        return $user->is_cashier;
    }

    public function import(User $user): bool {
        return false;
    }
}
