<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        return [
            'title' => 'required|string|max:255' ,
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:1' ,
            'compare_price' => 'nullable|numeric|gt:price' ,
            'status'=> 'nullable|in:active,inactive,archived',
            'stock' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image' ,
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'يجب أن تضع اسم المنتج' ,
            'description.required' => 'يجب أن تضع وصف المنتج' ,
            'description.max' => 'يجب أن وصف المنتج لا يتعدي 500 حرف ' ,
            'price.required' => 'يجب أن تضع سعر المنتج' ,
            'price.numeric' => ' يجب أن يكون سعر المنتج قيمة رقمية' ,
            'category_id.required' => 'يجب أن تختار القسم للمنتج' ,
            'stock.required' => 'يجب أن تضع مخزون المنتج' ,
            'compare_price.gt' => 'يجب أن يكون السعر الوهمي أكبر من السعر الاساسي' ,
            'image.required' => 'يجب اختيار صورة للمنتج.',
            'image.image'    => 'الملف يجب أن يكون صورة.',
            'image.mimes'    => 'الصيغ المسموحة هي: jpeg, png, jpg.',
            'image.max'      => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت.',
        ];
    }
}
