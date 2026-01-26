<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'invoice' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'invoice.required' => 'يجب اختيار رفع الفاتورة .',
            'invoice.image'    => 'الفاتورة يجب أن تكون صورة.',
            'invoice.mimes'    => 'الصيغ المسموحة بها لرفع الفاتورة هي: jpeg, png, jpg.',
            'invoice.max'      => 'حجم الفاتورة لا يجب أن يتجاوز 2 ميجابايت.',
        ];
    }
}
