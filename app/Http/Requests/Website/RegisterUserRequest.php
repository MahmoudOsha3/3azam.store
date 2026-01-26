<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:30|min:10' ,
            'email'=> 'required|email|unique:users,email' ,
            'password' => 'required|string|max:20|min:8|confirmed',
            'address' => 'required|string|min:10|max:255' ,
            'delivery_id' => 'required|exists:deliveries,id',
            'phone' => 'required|digits:11' ,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'يجب إدخال الاسم' ,
            'name.max' => 'يجب أن يكون الاسم اقل من 30 حرف ' ,
            'name.min' => 'يجب أن يكون الاسم أكثر من 10 حروف ',
            'email.required' => 'يجب إدخال البريد الالكنروني' ,
            'email.unique' => 'هذا البريد الالكتروني مسجل مسبقا ' ,
            'password.required' => 'يجب إدخال كلمة المرور' ,
            'password.max' => 'يجب أن تكون كلمة السر اقل من  20 حرف ' , 
            'password.min' => 'يجب كلمة السر ان لاتتعدي 8 حروف ' ,
            'address.required' => 'يجب إدخال العنوان ' ,
            'address.min' => 'العنوان يجب أن يكون اكثر من 10 حروف ',
            'delivery_id.required' => 'يجب اختيار محافظة ',
            'phone.required' => 'رقم التليفون مطلوب' ,
            'phone.digits' => 'رقم التليفون يجب أن يكون 11 رقم' ,

        ] ;
    }
}
