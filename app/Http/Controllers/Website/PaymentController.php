<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\PaymentRequest;
use App\Models\Payment;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use League\Config\Exception\ValidationException;

class PaymentController extends Controller
{
    use ManageApiTrait;
    public function store(PaymentRequest $request, $orderId)
    {
        try {
            $ImageName = null;

            if ($request->hasFile('invoice')) {
                $ImageName = $this->upload($request->file('invoice'));
            }

            $payment = Payment::create([
                'user_id' => Auth::id(),
                'order_id' => $orderId,
                'invoice' => $ImageName,
            ]);
            return $this->successApi($payment, 'تم رفع الفاتورة بنجاح');
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء حفظ البيانات'], 500);
        }
    }

    public function upload($newImage)
    {
        $ImageName = Str::uuid() . "." . $newImage->getClientOriginalExtension() ;
        $newImage->storeAs('', $ImageName , 'payments') ;
        return $ImageName ;
    }
}
