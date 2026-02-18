<?php

namespace App\Policies\Dashboard;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function dashboardView(Admin $admin)
    {
        return $admin->hasPermission('dashboard.view') ;
    }

    public function ordersView(Admin $admin)
    {
        return $admin->hasPermission('order.view');
    }

    public function AdminsView(Admin $admin)
    {
        return $admin->hasPermission('admin.view');
    }

    public function DeliveryView(Admin $admin)
    {
        return $admin->hasPermission('delivery.view');
    }

    public function deliveryUpdate(Admin $admin)
    {
        return $admin->hasPermission('delivery.update');
    }


    public function usersView(Admin $admin)
    {
        return $admin->hasPermission('user.view') ;
    }

    public function userDelete(Admin $admin)
    {
        return $admin->hasPermission('user.delete') ;
    }

    public function paymentsView(Admin $admin)
    {
        return $admin->hasPermission('payment.view') ;
    }

}
