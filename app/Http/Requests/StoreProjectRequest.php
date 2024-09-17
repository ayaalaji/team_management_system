<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role == 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>'required|string|min:4|max:100',
            'description' =>'required|string|min:10|max:255',
            'users' => 'nullable|array',
            'users.*.user_id' => 'required|exists:users,id',
            'users.*.role' => 'required|in:manager,developer,tester',
            'users.*.contribution_hours' => 'nullable|integer|min:0',
            'users.*.tasks' => 'nullable|array',
            'users.*.tasks.*' => 'exists:tasks,id',
        ];
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
            'name' =>'اسم المشروع',
            'description' =>'توصيف المشروع',
            'users' => 'بيانات المستخدمين',
            'users.*.user_id' => 'معرف المستخدم',
            'users.*.role' => 'دور المستخدم',
            'users.*.contribution_hours' => 'ساعات المساهمة',
            'users.*.tasks' => 'المهام',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'حقل :attribute هو حقل إجباري.',
            'string' => 'حقل :attribute يجب أن يكون قيمة نصية.',
            'min' => 'حقل :attribute يجب أن يحتوي على الأقل :min حرفاً.',
            'max' => 'حقل :attribute لا يمكن أن يتجاوز :max حرفاً.',
            'exists' => 'الـ :attribute المحدد غير موجود.',
            'in' => 'القيمة المحددة في :attribute غير صحيحة.',
            'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
        ];
    }
}
