<?php

namespace App\Http\Requests\CustomerRequests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customer = $this->route('customer');
        return [
            'name' => 'required',
            'no_telp' => 'required|unique:customers,no_telp,' . $customer->id,
            'address' => 'required',
        ];
    }
}
