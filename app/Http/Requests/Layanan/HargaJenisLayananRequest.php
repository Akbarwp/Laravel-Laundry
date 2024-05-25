<?php

namespace App\Http\Requests\Layanan;

use Illuminate\Foundation\Http\FormRequest;

class HargaJenisLayananRequest extends FormRequest
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
            'harga' => 'required|decimal:0,2',
            'jenis_satuan' => 'required|string|max:255',
            'jenis_layanan_id' => 'required|integer',
            'jenis_pakaian_id' => 'required|integer',
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
