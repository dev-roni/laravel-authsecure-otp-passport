<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email|max:55',
            'phone' => 'required|string|unique:users,phone|regex:/^01\d{9}$/',
            'password' => 'required|string|min:8|max:20',
        ];
    }

    //এপআই এর জন্য
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()  or $this->is('api/*')) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validator->errors(),
                ], 422)
            );
        }
        // Web request হলে default behaviour
        parent::failedValidation($validator);
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'নাম দেওয়া আবশ্যক',
            'name.min'       => 'নাম কমপক্ষে ৩ অক্ষরের হতে হবে',
            'name.max'       => 'নাম সর্বোচ্চ ৫০ অক্ষরের হতে হবে',

            'email.required' => 'ইমেইল দেওয়া আবশ্যক',
            'email.email'    => 'সঠিক ইমেইল ফরম্যাট দিন',
            'email.unique'   => 'এই ইমেইল আগেই ব্যবহার হয়েছে',
            'email.max'      => 'এই ইমেইল সর্বোচ্চ ৫৫ অক্ষরের হতে হবে',

            'phone.required' => 'ফোন নম্বর দেওয়া আবশ্যক',
            'phone.unique'   => 'এই ফোন নম্বর আগেই ব্যবহার হয়েছে',
            'phone.regex'   => '01 দিয়ে শুরু কর এবং ভেলিড নাম্বার দাও',

            'password.required'  => 'পাসওয়ার্ড দেওয়া আবশ্যক',
            'password.min'       => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে',
            'password.max'       => 'পাসওয়ার্ড সর্বোচ্চ ২০ অক্ষরের হতে হবে',
        ];
    }
}
