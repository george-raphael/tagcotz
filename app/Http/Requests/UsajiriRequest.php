<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsajiriRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|unique:attendances',
            'region_id' => 'required',
            'district_id' => 'required',
            'email' => 'required|unique:attendances',
            'institution' => 'required',
            'title' => 'required',
            'receipt_file' => 'required|max:10240'
        ];
    }
}
