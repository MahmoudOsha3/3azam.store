<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        return [
            'name' => 'required|string|min:4|max:30',
            'parent_id' => 'nullable|exists:categories,id' ,
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image' ,
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ]
        ];
    }
}
