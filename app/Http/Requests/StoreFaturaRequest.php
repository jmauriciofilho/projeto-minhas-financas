<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaturaRequest extends FormRequest
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
            'mes_referencia' => [
                'required',
                'date_format:Y-m'
            ],
            'data_fechamento' => [
                'required',
                'date'
            ],
            'data_vencimento' => [
                'required',
                'date'
            ]
        ];
    }
}
