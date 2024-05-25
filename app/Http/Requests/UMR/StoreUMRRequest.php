<?php

namespace App\Http\Requests\UMR;

use Illuminate\Foundation\Http\FormRequest;

class StoreUMRRequest extends FormRequest
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
            'regional' => 'required|string|max:255',
            'upah' => 'required|decimal:0,2',
            'tahun' => 'required|integer|unique:App\Models\UMR,tahun',
            'is_used' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ];
    }
}
