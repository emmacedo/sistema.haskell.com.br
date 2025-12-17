<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistributorRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255'],
            'trade_name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:distributors,cnpj'],
            'email' => ['required', 'email', 'max:255', 'unique:distributors,email'],
            'phone' => ['required', 'string', 'max:20'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'cep' => ['nullable', 'string', 'max:10'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:2'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'cities' => ['required', 'array', 'min:1'],
            'cities.*' => ['exists:cities,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'company_name' => 'razão social',
            'trade_name' => 'nome fantasia',
            'cnpj' => 'CNPJ',
            'email' => 'e-mail',
            'phone' => 'telefone',
            'phone2' => 'telefone 2',
            'whatsapp' => 'WhatsApp',
            'website' => 'website',
            'cep' => 'CEP',
            'logradouro' => 'logradouro',
            'numero' => 'número',
            'complemento' => 'complemento',
            'bairro' => 'bairro',
            'cidade' => 'cidade',
            'estado' => 'estado',
            'status' => 'status',
            'cities' => 'cidades',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cities.required' => 'Selecione pelo menos uma cidade.',
            'cities.min' => 'Selecione pelo menos uma cidade.',
        ];
    }
}
