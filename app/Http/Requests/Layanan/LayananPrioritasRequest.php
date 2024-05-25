<?php

namespace App\Http\Requests\Layanan;

use Illuminate\Foundation\Http\FormRequest;

class LayananPrioritasRequest extends FormRequest
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
            'jenis_satuan' => 'required|string|max:255',
            'harga' => 'required|decimal:0,2',
            'prioritas' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ];
    }
}
