<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchasePolicy
{
    public function before(User $user, $ability): bool|null{
        if ($user->isAdmin() || $user->isOwner()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool{
        return $user->isCashier();
    }

    public function view(User $user, Purchase $purchase): bool{
        return $user->isCashier();
    }

    public function create(User $user): bool{
        return false;
    }

    public function update(User $user, Purchase $purchase): bool{
        return false;
    }

    public function delete(User $user, Purchase $purchase): bool{
        return false;
    }

    public function restore(User $user, Purchase $purchase): bool{
        return false;
    }

    public function forceDelete(User $user, Purchase $purchase): bool{
        return false;
    }
}
