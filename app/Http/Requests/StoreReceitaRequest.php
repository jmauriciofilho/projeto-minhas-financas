<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceitaRequest extends FormRequest
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
            'nome' => [
                'required',
                'string',
                'min:3',
                'max:120'
            ],

            'valor' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'mes' => [
                'required',
                'date_format:Y-m'
            ],

            'conta_id' => [
                'required',
                'uuid',
                'exists:contas,id'
            ],
        ];
    }
}
