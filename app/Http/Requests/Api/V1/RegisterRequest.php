<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxDate = now()->subYears(21)->format('Y-m-d');

        return [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birth_date' => ['required', 'date', "before_or_equal:{$maxDate}"],
        ];
    }

    public function messages(): array
    {
        return [
            'birth_date.before_or_equal' => 'Anda harus berusia minimal 21 tahun untuk mendaftar di Teman Tuang.',
        ];
    }
}