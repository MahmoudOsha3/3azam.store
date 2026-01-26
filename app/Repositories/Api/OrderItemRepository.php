<?php

namespace App\Repositories\Api ;

use App\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{

    public function getOrders()
    {

    }

    public function create($order , $carts)
    {
        $items = [] ;
        foreach($carts as $key => $cart)
        {
            $items[] =  [
                'order_id' => $order->id ,
                'product_id' => $cart->product->id ,
                'product_name' => $cart->product->title ,
                'price' => $cart->product->price ,
                'sku' => $cart->product->sku ,
                'quantity' => $cart->quantity ,
                'total' => $cart->quantity * $cart->product->price ,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        }
        $OrderItem = OrderItem::insert($items);
        return $OrderItem ;
    }



    public function update($request , $order)
    {

    }

    public function delete(OrderItem $order)
    {
        $order->delete() ;
    }
}




