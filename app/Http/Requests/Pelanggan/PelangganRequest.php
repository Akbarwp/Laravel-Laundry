<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;

class PelangganRequest extends FormRequest
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
        $userRole = auth()->user()->roles[0]->name;
        if ($userRole != 'lurah') {
            $this->request->add(['cabang_id' => auth()->user()->cabang_id]);
        }
        return [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1|in:L,P',
            'telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'cabang_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ];
    }
}
