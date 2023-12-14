<?php

namespace App\Http\Requests\ProfitRequests;

use Illuminate\Foundation\Http\FormRequest;

class FilterProfitRequest extends FormRequest
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
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }
}
