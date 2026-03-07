<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
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
            'descricao' => 'required|string|max:255',
            'data_compra' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'total_parcelas' => 'required|integer|min:1',
            'numero_parcela' => 'required|integer|min:1|max:' . $this->input('total_parcelas')
        ];
    }
}
