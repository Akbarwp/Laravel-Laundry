<?php

namespace App\Http\Requests\Gamis;

use Illuminate\Foundation\Http\FormRequest;

class GamisRequest extends FormRequest
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
            'kartu_keluarga' => 'required|string|max:20',
            'alamat' => 'nullable',
            'rt' => 'required|integer',
            'rw' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
        ];
    }
}
