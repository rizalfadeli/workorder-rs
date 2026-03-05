<?php
namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    /** Admin bisa lihat semua, user hanya punyanya sendiri */
    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->isAdmin() || $workOrder->user_id === $user->id;
    }

    /** Hanya admin yang bisa update work order */
    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->isAdmin();
    }

    /** Hanya admin yang bisa hapus */
    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->isAdmin();
    }

    /** User bisa chat jika work order miliknya atau admin */
    public function chat(User $user, WorkOrder $workOrder): bool
    {
        return $user->isAdmin() || $workOrder->user_id === $user->id;
    }
}