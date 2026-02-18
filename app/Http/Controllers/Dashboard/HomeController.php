<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Policies\Dashboard\DashboardPolicy;
use App\Repositories\Api\OrderRepository;
use App\Repositories\Api\ProductRepository;
use App\Services\Home\LatestOrdersServices;
use App\Services\Home\ManageDataService;
use App\Services\Home\ProfitService;
use App\Traits\ManageApiTrait;

class HomeController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository , $productRepoistory , $profitService , $manageDataService , $latestOrders , $invoiceRepository ;

    public function __construct(
        OrderRepository $orderRepository ,
        ProductRepository $productRepoistory ,
        ProfitService $profitService ,
        ManageDataService $manageDataService ,
        LatestOrdersServices $latestOrders ) {

        $this->orderRepository = $orderRepository;
        $this->productRepoistory = $productRepoistory;
        $this->profitService = $profitService;
        $this->manageDataService = $manageDataService ;
        $this->latestOrders = $latestOrders ;
    }

    public function stat()
    {
        $data = $this->manageDataService->data(
            $this->orderRepository->countOrders() ,
            $this->productRepoistory->countProducts() ,
            $this->profitService->day() ,
            $this->profitService->amonth() ,
            $this->orderRepository->countOrderToDay(),
            $this->orderRepository->countOrderToMonth(),

        ) ;
        return $this->successApi($data, 'Data fetched successfully');
    }
    public function index()
    {
        $this->authorize('dashboardView' , Admin::class) ;
        return view('pages.dashboard.home.index') ;
    }
}
