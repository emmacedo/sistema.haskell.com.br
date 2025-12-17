<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Buscando cidades da API do IBGE...');

        $states = State::all();

        foreach ($states as $state) {
            $this->command->info("Processando: {$state->name} ({$state->uf})");

            try {
                $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state->uf}/municipios");

                if ($response->successful()) {
                    $cities = $response->json();

                    foreach ($cities as $cityData) {
                        City::updateOrCreate(
                            ['ibge_code' => $cityData['id']],
                            [
                                'state_id' => $state->id,
                                'name' => $cityData['nome'],
                            ]
                        );
                    }

                    $this->command->info("  ✓ " . count($cities) . " cidades cadastradas");
                } else {
                    $this->command->error("  ✗ Erro ao buscar cidades de {$state->uf}");
                }

                // Pequeno delay para não sobrecarregar a API
                usleep(300000); // 0.3 segundos

            } catch (\Exception $e) {
                $this->command->error("  ✗ Erro: " . $e->getMessage());
            }
        }

        $totalCities = City::count();
        $this->command->info("Total de cidades cadastradas: {$totalCities}");
    }
}
