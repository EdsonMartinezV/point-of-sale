<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    public function before(User $user, string $ability): bool|null{
        if ($user->is_admin || $user->is_owner) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool{
        return $user->is_cashier;
    }

    public function view(User $user, Sale $sale): bool{
        return $user->is_cashier;
    }

    public function create(User $user): bool{
        return $user->is_cashier;
    }

    public function update(User $user, Sale $sale): bool{
        return false;
    }

    public function delete(User $user, Sale $sale): bool{
        return false;
    }

    public function restore(User $user, Sale $sale): bool{
        return false;
    }

    public function forceDelete(User $user, Sale $sale): bool{
        return false;
    }
}
