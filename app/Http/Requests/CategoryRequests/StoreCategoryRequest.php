<?php

namespace App\Http\Requests\CategoryRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2000',
            'name' => 'required|unique:categories',
            'description' => 'required'
        ];
    }
}
