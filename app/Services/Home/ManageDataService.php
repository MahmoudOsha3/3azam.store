<?php

namespace App\Services\Home;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;

class ManageDataService
{
public function data($countOrders, $countProducts, $profitOfDay, $profitOfMonth , $countOrderToDay , $countOrderToMonth)
{
    $countUsers  = User::count();
    $countAdmins = Admin::count();


    return [
        'countOrders'       => $countOrders,
        'countProducts'     => $countProducts,
        'profitOfDay'       => $profitOfDay,
        'profitOfMonth'     => $profitOfMonth,
        'countUsers'        => $countUsers,
        'countOrderToMonth' => $countOrderToMonth ,
        'countOrderToDay'  => $countOrderToDay ,
        'countAdmins'      => $countAdmins ,
        'chart_data'        => [
            'profit_labels' => ['4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm'],
            'profit_values' => [1200, 1900, 1500, 2500, 3200, 2800, 3500],
        ],
    ];
}


}

