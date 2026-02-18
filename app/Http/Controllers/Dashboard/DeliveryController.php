<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Delivery;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    use ManageApiTrait ;
    public function view()
    {
        $this->authorize('DeliveryView' , Admin::class );
        return view('pages.dashboard.delivery.index') ;
    }

    public function index()
    {
        $this->authorize('DeliveryView' , Admin::class );
        $deliveries = Delivery::all() ;
        return $this->successApi($deliveries , 'Delivery fetched successfully') ;
    }

    public function update(Request $request, Delivery $delivery)
    {
        $this->authorize('deliveryUpdate' , Admin::class );
        $request->validate(['tax' => 'required|numeric|min:0']) ;
        $delivery->update(['tax' => $request->tax]) ;
        return $this->successApi($delivery , 'Delivery updated successfully') ;
    }

}
