<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingUserRequest extends FormRequest
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
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
            're_password' => 'nullable|same:password',
            'school_name' => 'required|min:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:800',
        ];
    }
}
