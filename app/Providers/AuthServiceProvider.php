<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Policies\Dashboard\CategoryPolicy;
use App\Policies\Dashboard\CouponPolicy;
use App\Policies\Dashboard\DashboardPolicy;
use App\Policies\Dashboard\OrderPolicy;
use App\Policies\Dashboard\ProductPolicy;
use App\Policies\Dashboard\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Product::class => ProductPolicy::class ,
        Category::class => CategoryPolicy::class ,
        Admin::class => DashboardPolicy::class ,
        Order::class => OrderPolicy::class ,
        Coupon::class => CouponPolicy::class ,
        Role::class => RolePolicy::class


    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
