<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'thumbnail' => 'nullable|image',
            'name' => 'required|string',
            'about' => 'required|string',
            'headman' => 'required|string',
            'people' => 'required|integer',
            'agriculture_area' => 'required',
            'total_area' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'required|image|mimes:png,jpg|max:2048',
        ];
    }

    public function attributes(){
        return [
            'thumbnail' => 'Thumbnail',
            'name' => 'Nama',
            'about' => 'Deskripsi',
            'headman' => 'Kepala Desa',
            'people' => 'Jumlah Penduduk',
            'agriculture_area' => 'Luas Pertanian',
            'total_area' => 'Luas Total',
            'images' => 'Gambar',
        ];
    }
}
