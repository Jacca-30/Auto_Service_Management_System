<?php

namespace App\Policies;

use App\Models\RepairOrder;
use App\Models\User;

class RepairOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function view(User $user, RepairOrder $repairOrder): bool
    {
        return $user->tenant_id === $repairOrder->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function update(User $user, RepairOrder $repairOrder): bool
    {
        return $user->tenant_id === $repairOrder->tenant_id;
    }

    public function delete(User $user, RepairOrder $repairOrder): bool
    {
        return false;
    }

    public function restore(User $user, RepairOrder $repairOrder): bool
    {
        return false;
    }

    public function forceDelete(User $user, RepairOrder $repairOrder): bool
    {
        return false;
    }
}
