<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        $rules =  [
            'user_id' => 'nullable|exists:users,id',
            'quantity' => 'required|numeric|min:-1|max:30', // -1 removed
        ];

        if ($this->isMethod('post')) {
            $rules['product_id'] = 'required|exists:products,id';
        }
        return $rules ;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422)
        );
    }

}
