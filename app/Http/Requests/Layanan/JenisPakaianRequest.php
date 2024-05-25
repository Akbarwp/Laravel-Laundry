<?php

namespace App\Http\Requests\Layanan;

use Illuminate\Foundation\Http\FormRequest;

class JenisPakaianRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ];
    }
}
