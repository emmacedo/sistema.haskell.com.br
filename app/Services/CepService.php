<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CepService
{
    /**
     * Busca informações de um CEP usando a API ViaCEP
     *
     * @param string $cep
     * @return array|null
     */
    public function buscar(string $cep): ?array
    {
        $cep = $this->limparCep($cep);

        if (!$this->validarCep($cep)) {
            return null;
        }

        // Cache por 30 dias
        return Cache::remember("cep:{$cep}", now()->addDays(30), function () use ($cep) {
            try {
                $response = Http::timeout(10)->get("https://viacep.com.br/ws/{$cep}/json/");

                if ($response->successful()) {
                    $data = $response->json();

                    // ViaCEP retorna erro = true quando CEP não existe
                    if (isset($data['erro']) && $data['erro'] === true) {
                        return null;
                    }

                    return [
                        'cep' => $data['cep'] ?? null,
                        'logradouro' => $data['logradouro'] ?? null,
                        'complemento' => $data['complemento'] ?? null,
                        'bairro' => $data['bairro'] ?? null,
                        'cidade' => $data['localidade'] ?? null,
                        'uf' => $data['uf'] ?? null,
                        'ibge' => $data['ibge'] ?? null,
                    ];
                }

                return null;
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar CEP: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Limpa formatação do CEP
     *
     * @param string $cep
     * @return string
     */
    private function limparCep(string $cep): string
    {
        return preg_replace('/[^0-9]/', '', $cep);
    }

    /**
     * Valida formato do CEP
     *
     * @param string $cep
     * @return bool
     */
    private function validarCep(string $cep): bool
    {
        return strlen($cep) === 8 && is_numeric($cep);
    }
}
