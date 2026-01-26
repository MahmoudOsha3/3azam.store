<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\OrderRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Api\CartRepository;
use App\Repositories\Api\OrderRepository;
use App\Services\Orders\OrderServices;


class OrderController extends Controller
{
    protected $orderRepoistory , $cartRepository , $orderServices ;
    public function __construct(OrderRepository $orderRepoistory , CartRepository $cartRepository , OrderServices $orderServices)
    {
        $this->orderRepoistory = $orderRepoistory ;
        $this->cartRepository = $cartRepository ;
        $this->orderServices = $orderServices ;
    }

    public function orders()
    {
        $orders = Order::with('orderItems')->where('user_id' , auth()->user()->id )->latest()->get() ;
        return view('pages.website.order.index' , compact('orders'));
    }

    public function store(OrderRequest $request)
    {
        try {
            $order = $this->orderRepoistory->create($request);
            return to_route('order.show' , $order->id ) ;
        }catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($orderId)
    {
        $order = Order::with(['orderItems' , 'couponUsage.coupon'])->where(['id' => $orderId , 'user_id' => auth()->user()->id])->firstOrFail() ;
        $payment = Payment::where(['order_id' => $orderId , 'user_id'=> auth()->user()->id])->first();
        return view('pages.website.order.show' , compact('order' , 'payment'));
    }


}
