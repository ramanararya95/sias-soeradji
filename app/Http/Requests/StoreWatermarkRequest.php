<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWatermarkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'file' => 'required|file|max:10240', // Maks 10MB
        ];
    }
    
    public function messages()
    {
        return [
            'file.required' => 'File harus dipilih',
            'file.max' => 'Ukuran file maksimal 10MB',
        ];
    }
}