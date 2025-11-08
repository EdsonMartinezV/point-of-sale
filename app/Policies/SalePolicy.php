<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    public function before(User $user, string $ability): bool|null{
        if ($user->isAdmin() || $user->isOwner()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool{
        return $user->isCashier();
    }

    public function view(User $user, Sale $sale): bool{
        return $user->isCashier();
    }

    public function create(User $user): bool{
        return $user->isCashier();
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
