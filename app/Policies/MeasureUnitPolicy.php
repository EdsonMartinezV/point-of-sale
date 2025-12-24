<?php

namespace App\Policies;

use App\Models\MeasureUnit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MeasureUnitPolicy
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

    public function view(User $user, MeasureUnit $measureUnit): bool {
        return $user->is_cashier;
    }

    public function create(User $user): bool {
        return $user->is_cashier;
    }

    public function update(User $user, MeasureUnit $measureUnit): bool {
        return $user->is_cashier;
    }

    public function delete(User $user, MeasureUnit $measureUnit): bool {
        return $user->is_cashier;
    }

    public function restore(User $user, MeasureUnit $measureUnit): bool {
        return $user->is_cashier;
    }

    public function forceDelete(User $user, MeasureUnit $measureUnit): bool {
        return $user->is_cashier;
    }
}
