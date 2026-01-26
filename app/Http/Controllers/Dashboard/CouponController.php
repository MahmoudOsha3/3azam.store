<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CouponRequest;
use App\Models\Coupon;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use ManageApiTrait ;

    public function view()
    {
        return view('pages.dashboard.coupons.index') ;
    }

    public function index(Request $request)
    {
        $coupons = Coupon::latest()->filter($request)->paginate(15) ;
        return $this->successApi($coupons , 'Coupons fetched successfully');
    }

    public function store(CouponRequest $request)
    {
        try{
            $validate = $request->validated() ;
            $coupon = Coupon::create($validate);
            return $this->successApi($coupon ,'Coupon created successfully') ;
        }catch(\Exception $e){
            return $this->failedApi($e->getMessage() , 500 ) ;
        }
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        try
        {
            $validate = $request->validated() ;
            $coupon->update($validate) ;
            return $this->successApi($coupon ,'Coupon updated successfully') ;
        }
        catch(\Exception $e)
        {
            return $this->failedApi($e->getMessage() , 500 ) ;
        }
    }

}
