<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Api\OrderRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository ;
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->getOrders($request);
        return view('pages.dashboard.orders.index' , compact('orders')) ;
    }

    public function store(Request $request)
    {
        $order = $this->orderRepository->create($request) ;
        return $this->successApi($order  , 'Order created successfully') ;
    }

    public function show(Order $order)
    {
        $order = $this->orderRepository->getOrder($order) ;
        return $this->successApi($order  , 'Order created successfully') ;
    }

    // update status of order
    public function update(Request $request, Order $order)
    {
        $order = $this->orderRepository->cahengeStatus($request , $order);
        return redirect()->back()->with('success' , 'تم تحديث الطلب بنجاح ') ;
    }

    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order) ;
        return $this->successApi(null , 'Order deleted successfully') ;
    }

    public function changeStatusPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid' ,
            ]) ;
        $order->update([
            'payment_status' => $request->payment_status
        ]) ;
        return redirect()->back()->with('success' , 'تم تحديث الطلب بنجاح ') ;

    }
}


