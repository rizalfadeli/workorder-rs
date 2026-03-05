<?php
namespace App\Providers;

use App\Models\WorkOrder;
use App\Policies\WorkOrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(WorkOrder::class, WorkOrderPolicy::class);

        // Gate: admin check
        Gate::define('admin', fn ($user) => $user->isAdmin());
    }
}