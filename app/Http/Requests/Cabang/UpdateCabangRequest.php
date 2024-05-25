<?php

namespace App\Http\Requests\Cabang;

use App\Models\Cabang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCabangRequest extends FormRequest
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
        $cabang = Cabang::find($this->id);
        return [
            'nama' => ['required', 'string', 'max:255', Rule::unique('cabang')->ignore($cabang)],
            'lokasi' => 'required|string|max:255',
            'alamat' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ];
    }
}
