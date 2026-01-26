<?php

use App\Http\Controllers\Dashboard\AuthController ;
use App\Http\Controllers\Dashboard\{AdminController, HomeController ,CategoryController, CouponController, DeliveryController, PaymentController, RoleController, UserController};
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])->prefix('admin')->group(function(){

    Route::get('dashboard/stat' , [HomeController::class ,'stat'])->name('dashboard.stat') ;
    Route::get('dashboard' , [HomeController::class ,'index'])->name('dashboard') ;

    Route::get('categories' ,[CategoryController::class , 'view']);
    Route::resource('category' , CategoryController::class) ;

    Route::get('products' ,[ProductController::class , 'view']);
    Route::resource('product' , ProductController::class) ;

    Route::get('deliveries', [DeliveryController::class , 'view']) ;
    Route::resource('delivery', DeliveryController::class);

    Route::get('users', [UserController::class , 'view']) ;
    Route::resource('user', UserController::class) ;

    Route::get('coupons', [CouponController::class , 'view'])->name('copons.view') ;
    Route::get('coupons/fetched', [CouponController::class , 'index'])->name('coupons.index');
    Route::post('coupons', [CouponController::class , 'store'])->name('coupons.store');
    Route::put('coupons/{coupon}', [CouponController::class , 'update'])->name('coupons.update') ;

    Route::get('roles' , [RoleController::class , 'view']) ;
    Route::resource('role' , RoleController::class) ;

    Route::get('payments' , [PaymentController::class , 'view']) ;
    Route::resource('payment' , PaymentController::class) ;

    Route::get('admins' , [AdminController::class , 'view']) ;
    Route::resource('admin' , AdminController::class) ;


    Route::resource('orders' , OrderController::class) ;
    Route::put('orders/{order}/paymentStatus' , [OrderController::class , 'changeStatusPayment'])
                ->name('order.payment.status') ;



    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

    Route::middleware('guest')->prefix('auth')->group(function () {
        Route::get('admin/login', [AuthController::class, 'loginView'])->name('login.view') ;
        Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');
    });

