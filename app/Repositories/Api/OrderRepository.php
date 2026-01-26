<?php

namespace App\Repositories\Api ;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Services\Coupon\CouponService;
use App\Services\Orders\OrderServices;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderRepositoryInterface
{
    protected $orderService , $cartRepository , $orderItemRepository , $productRepoistory , $couponService ;

    public function __construct(OrderServices $orderService ,
        CartRepository $cartRepository ,
        OrderItemRepository $orderItemRepository , ProductRepository $productRepoistory , CouponService $couponService){

        $this->orderService = $orderService;
        $this->cartRepository = $cartRepository ;
        $this->orderItemRepository = $orderItemRepository ;
        $this->productRepoistory = $productRepoistory ;
        $this->couponService = $couponService ;

    }

    public function getOrders($request)
    {
        $orders  = Order::with(['orderItems:id,order_id,product_name,price,quantity,total' , 'user:id,name,phone' ])
            ->filter($request)->latest()->paginate(15);
        return $orders ;
    }

    public function create($request)
    {
        DB::beginTransaction() ;
        try{
            $couponData = ['discount' => 0, 'coupon' => null];
            $user = auth()->user() ;
            $carts = $this->cartRepository->getCarts($user->id);
            if ($carts->isEmpty()) {
                throw new Exception('Cart is empty');
                Log::info("Happen This : Cart is empty when create Order") ;
            }

            foreach($carts as $cart)
            {
                $this->productRepoistory->checkStock($cart->product_id , $cart->quantity) ;
            }

            $subTotal = $this->orderService->subTotalOrder($carts) ;

            if ($request->filled('coupon_code')) {
                $couponData = $this->couponService->applyCoupon($request->coupon_code , $subTotal);
                if (!$couponData['status']) {
                    throw new \Exception('الكوبون غير صحيح او غير صالح للإستخدام') ;
                }
            }

            $total = $this->orderService->totalOrder($subTotal , $couponData['discount']);

            $order = Order::create([
                'user_id' =>  $user->id ,
                'subtotal' => $subTotal ,
                'type_payment' => $request['type_payment'] ,
                'delivery_fee' => $user->delivery->tax ,
                'total_price' => $total
            ]);

            $this->orderItemRepository->create($order , $carts);
            $this->orderService->decreaseStockAndIncreaseSalesCount($carts) ;

            if ($couponData['coupon']) {
                $this->couponService->recordCouponUsage($couponData['coupon'] , $order , $subTotal , $couponData['discount'] );
            }

            $this->cartRepository->deleteAll($carts) ;

            DB::commit() ;
            return $order ;
        }
        catch(\Exception $e)
        {
            DB::rollBack() ;
            throw  $e ;
        }
    }

    public function getOrder($order)
    {
        if(! is_object($order)) // $order here is order_id
        {
            $order = Order::with('orderItems:order_id,product_name,price,quantity,total')->findorfail($order) ;
        }
        $order->load(['orderItems:order_id,product_name,price,quantity,total' , 'couponUsage']) ;
        return $order ;
    }

    // in dashboard
    public function update($request , $order)
    {
        $order->update([
            'payment_status' => 'paid' ,
            'status' => 'confirmed'
        ]) ;
    }

    public function delete(Order $order)
    {
        $order->delete() ;
    }

    public function cahengeStatus($request , $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivering,cancelled,completed'
        ]);
        $order = $order->update([
            'status' => $request->status
        ]) ;
        return $order ;
    }

    public function countOrders()
    {
        return Order::count() ;
    }

    public function countOrderToDay()
    {
        $count = Order::whereDate('created_at' , today())->count() ;
        return $count ;
    }

    public function countOrderToMonth()
    {
        $count = Order::whereDate('created_at' , now()->month)->count() ;
        return $count ;
    }
}




