<?php

namespace App\Http\Controllers\Website ;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Api\CategoriesRepository;
use App\Traits\ManageApiTrait;

class CategoryController extends Controller
{
    use ManageApiTrait ;
    public $categoriesRepo ;
    public function __construct(CategoriesRepository $categoriesRepo)
    {
        $this->categoriesRepo = $categoriesRepo ;
    }
    public function index()
    {
        $categories = $this->categoriesRepo->getCategories() ;
        return view('pages.website.categories.index' , compact('categories'));
    }

    public function getProductsSpecialCategory($categoryId)
    {
        $products = $this->categoriesRepo->getProductsSpecialCategory($categoryId ) ;
        return $this->successApi($products , 'Products fetched successfully');
    }
}
