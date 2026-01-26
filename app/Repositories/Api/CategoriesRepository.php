<?php

namespace App\Repositories\Api ;

use App\Models\Category;
use App\Models\Product;

class CategoriesRepository
{

    public function getCategories()
    {
        $categories = Category::get() ;
        return $categories ;
    }

    public function getProductsSpecialCategory($categoryId)
    {
        $products = Product::where(['category_id' => $categoryId , 'status' => 'active'])
        ->with('category:id,name')->paginate(15) ;
        return $products ;
    }

    public function create($validated)
    {
        $category = Category::create($validated) ;
        return $category ;
    }

    public function update($request , $category)
    {
        //
    }

    public function delete(Category $category)
    {
        $category->delete() ;
    }

    public function deleteAll($categories)
    {
        Category::whereIn('id' , $categories->pluck('id'))->delete() ;
    }
}




