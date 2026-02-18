<?php

use App\Http\Controllers\Website\{CartController,HomeController, CategoryController, OrderController, PaymentController, ProductController, ProfileController};
use App\Http\Controllers\Website\{AuthController , SocialiteController };
use Illuminate\Support\Facades\Route;

    Route::get('/' , [HomeController::class , 'index'])->name('home');
    Route::resource('cart', CartController::class) ;
    Route::get('carts', [CartController::class , 'getCarts']) ;
    Route::get('categories' , [CategoryController::class , 'index'])->name('categories.index');
    Route::get('categories/products/{category}' , [CategoryController::class , 'getProductsSpecialCategory']);
    Route::get('product/{product}' , [ProductController::class , 'show']);


    Route::middleware('auth')->group(function(){
        Route::prefix('user')->group(function(){
            Route::get('order/checkout' , [CartController::class , 'index'])->name('carts.index');
            Route::post('order/store' , [OrderController::class , 'store'])->name('order.store');
            Route::get('orders' , [OrderController::class , 'orders'])->name('orders.me'); // all orders
            Route::get('orders/{order}' , [OrderController::class , 'show'])->name('order.show'); // details order
            Route::post('orders/{order}/upload_invoice' , [PaymentController::class , 'store'])
                    ->name('order.upload.invoice'); // upload invoice

        });

        // Authentication system
        // Route::prefix('user/profile')->name('user.')->controller(ProfileController::class)->group(function(){
        //     Route::get('/' , 'profile')->name('profile');
        //     Route::put('update' , 'update')->name('profile.update') ;
        // });
        Route::post('auth/logout' ,[AuthController::class , 'logout'])->name('auth.logout') ;
    }) ;

    // Authentication system
    Route::middleware('guest')->name('user.')->controller(AuthController::class)->group(function () {

        Route::get('login', 'login')->name('login.view');
        Route::post('login', 'checkLogin')->middleware('throttle:5,1')->name('login.check');

        Route::get('register', 'register')->name('create.view');
        Route::post('create', 'createUser')->name('store.site');
    });

    Route::prefix('app')->name('socialite.')->controller(SocialiteController::class)->group(function(){
        Route::get('login/{provider}' , 'login')
            ->where('provider' , 'facebook|google')->name('login') ;

        Route::get('redirect/{provider}' , 'redirect')->name('redirect')
            ->where('provider', 'facebook|google');
    });
