<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CLTLayerRequest extends FormRequest
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
            'layers'               => ['required', 'array', 'min:1'],
            'layers.*.id'          => ['nullable', 'integer', 'exists:clt_layers,id'],
            'layers.*.layer_order' => ['required', 'integer', 'min:1'],
            'layers.*.thickness'   => ['required', 'numeric', 'min:0.1'],
            'layers.*.width'       => ['required', 'numeric', 'min:0.1'],
            'layers.*.angle'       => ['required', 'in:0,90'],
            'layers.*.grade'       => ['required', 'in:C24,C16'],
        ];
    }

    /**
     * Custom attribute names for validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'layers'               => 'daftar layer',
            'layers.*.layer_order' => 'urutan layer',
            'layers.*.thickness'   => 'ketebalan',
            'layers.*.width'       => 'lebar',
            'layers.*.angle'       => 'sudut',
            'layers.*.grade'       => 'grade kayu',
        ];
    }
}
