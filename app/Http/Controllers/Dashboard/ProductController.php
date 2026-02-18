<?php

namespace App\Http\Controllers\Dashboard ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ProductRequest;
use App\Models\Product;
use App\Repositories\Api\ProductRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    protected $productRepoistory ;
    use ManageApiTrait ;

    public function __construct(ProductRepository $productRepoistory)
    {
        $this->productRepoistory = $productRepoistory;
    }

    public function view()
    {
        $this->authorize('viewAny' , Product::class ) ;
        return view('pages.dashboard.products.index') ;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny' , Product::class ) ;
        $products = $this->productRepoistory->getAll($request) ;
        return $this->successApi($products , 'The data was successfully extracted') ;
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('create' , Product::class ) ;
        $product = $this->productRepoistory->create($request->validated() , $request['_token']) ;
        return $this->createApi($product , 'Product is created successfully') ;
    }


    public function show(Product $product)
    {
        return $this->successApi($product , 'The data was successfully extracted');
    }

    public function update(ProductRequest $request, Product $product)
    {
        // $this->authorize('update' , Product::class ) ;
        $product = $this->productRepoistory->update($product , $request->validated()) ;
        return $this->createApi($product , 'Product is updated successfully') ;
    }

    public function destroy(Product $product)
    {
        // $this->authorize('delete' , Order::class ) ;
        $this->productRepoistory->delete($product) ;
        return $this->successApi(null , 'Product is deleted successfilly') ;
    }
}
