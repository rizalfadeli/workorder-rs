<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'item_name'   => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            // Gambar: opsional, max 5 file, masing-masing max 5MB
            'images'      => ['nullable', 'array', 'max:5'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'kategori' => ['required', 'in:jaringan,hardware,software,lainnya'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required'   => 'Nama barang wajib diisi.',
            'location.required'    => 'Lokasi/unit wajib diisi.',
            'description.required' => 'Deskripsi kerusakan wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
            'images.*.image'       => 'File harus berupa gambar.',
            'images.*.max'         => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}