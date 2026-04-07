<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    // API এর জন্য
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'email.required' => 'ইমেইল দেওয়া আবশ্যক',
            'email.email'    => 'সঠিক ইমেইল ফরম্যাট দিন',
            'email.exists'   => 'এই ইমেইল পাওয়া যায়নি',
        ];
    }
}