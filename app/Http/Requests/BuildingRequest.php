<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
            'name' => 'required|string|unique:buildings,name,'.$this->id,
            'shortcut' => 'required',
            'address' => 'nullable',
        ];
    }

    public function messages()
    {
        $message = [
            'shortcut' => 'Field Required',
        ];
        return $message;
    }
}
