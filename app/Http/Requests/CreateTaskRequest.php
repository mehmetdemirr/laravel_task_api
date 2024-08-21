<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"=> "required|string|max:255",
            "is_completed"=> "boolean|nullable",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'İsim alanı gereklidir.',
            'name.string' => 'İsim alanı bir metin olmalıdır.',
            'name.max' => 'İsim alanı en fazla 255 karakter uzunluğunda olabilir.',
            'is_completed.boolean' => 'Tamamlanma durumu geçerli bir boolean (true/false) değeri olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Bu metod, doğrulama hatası oluştuğunda çalışır
        $errors = $validator->errors();

        $errorMessages = $errors->all(); // Hata mesajlarını al
        $errorString = implode(', ', $errorMessages);

        // HTTP yanıtını özelleştirin
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'hata',
            'errors' => $errorString,
        ], 400));
    }
}
