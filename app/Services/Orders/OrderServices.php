<?php

namespace App\Services\Orders ;

use App\Models\Meal;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderServices
{
    public function subTotalOrder($carts)
    {
        $subTotal = 0;
        foreach ($carts as $cart) {
            if ($cart->product) {
                $subTotal += $cart->product->price * $cart->quantity ;
            }
        }
        return $subTotal;
    }

    public function totalOrder($subTotal , $couponValue = 0)
    {
        $total = ($subTotal - $couponValue ) + auth()->user()->delivery->tax ;
        return $total ;
    }


    public static function updateForPayment($order_id)
    {
        Order::where('id', $order_id)->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
        return true ;
    }


    public function decreaseStockAndIncreaseSalesCount($carts)
    {
        DB::transaction(function () use ($carts) {
            foreach ($carts as $cart)
            {
                $updated  = Product::where('id' , $cart->product_id)
                                ->where('stock' , '>=' , $cart->quantity)
                                ->update([
                                    'stock' => DB::raw("stock - {$cart->quantity}"),
                                    'sales_count' => DB::raw("sales_count + {$cart->quantity}"),
                                ]);
                if ($updated === 0){
                    throw new Exception("Not enough stock for product {$cart->product_id}");
                }
            }
        });
    }

}



