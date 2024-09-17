<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role == 'admin');
    }

     public function prepareForValidation()
    {
        $this->merge([
            'first_name' =>ucwords($this->input('first_name')),
            'last_name' =>ucwords($this->input('last_name'))
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       $userId = $this->route('user') ?? $this->input('user');
        return [
            'first_name' => 'nullable|string|min:3|max:100',
            'last_name' => 'nullable|string|min:3|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'nullable|string|min:8',
        ];
    }
     protected function passedValidation()
    {
        /**
         * merge fisrt name and last name to became full name
        */
        $this->merge([
            'User Name' =>$this->input('first_name') . ' ' . $this->input('last_name')
        ]);
    }
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Unauthorized: Only admins can add users.',
            ], 403)
        );
    }

    public function attributes()
    {
        return [
            'first_name' =>'الاسم الاول',
            'last_name' =>'الاسم الاخير',
            'email' =>'البريد الالكتروني',
            'password' =>'كلمة المرور',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'حقل :attribute هو حقل إجباري.',
            'string' => 'حقل :attribute يجب أن يكون قيمة نصية.',
            'min' => 'حقل :attribute يجب أن يحتوي على الأقل :min حرفاً.',
            'max' => 'حقل :attribute لا يمكن أن يتجاوز :max حرفاً.',
        ];
    }
}
