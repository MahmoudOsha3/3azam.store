<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ManageApiTrait;
    public function view()
    {
        return view('pages.dashboard.payments.index') ;
    }

    public function index(Request $request)
    {
        $payments = Payment::with(['user:id,name,phone','order:id,number_order,total_price'])->filter($request)
                        ->paginate(20);
        return $this->successApi($payments, 'Payment fetched successfully') ;
    }


}
