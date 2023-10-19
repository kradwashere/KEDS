<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'required|string',
            'nationality' => 'sometimes|required',
            'gender' => 'sometimes|required',
            'birthday' => 'sometimes|required',
            'mobile' => 'sometimes|required',
            'address' => 'sometimes|required',
        ];
    }
}
