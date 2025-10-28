<?php

namespace App\Policies;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProviderPolicy
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

    public function view(User $user, Provider $provider): bool {
        return $user->isCashier();
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, Provider $provider): bool {
        return false;
    }

    public function delete(User $user, Provider $provider): bool {
        return false;
    }

    public function restore(User $user, Provider $provider): bool {
        return false;
    }

    public function forceDelete(User $user, Provider $provider): bool {
        return false;
    }
}
