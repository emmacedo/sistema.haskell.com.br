<?php

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributors = [
            [
                'company_name' => 'Beleza & Cia Distribuidora Ltda',
                'trade_name' => 'Beleza & Cia',
                'cnpj' => '12.345.678/0001-90',
                'email' => 'contato@belezaecia.com.br',
                'phone' => '(11) 3456-7890',
                'phone2' => '(11) 3456-7891',
                'whatsapp' => '(11) 98765-4321',
                'website' => 'https://www.belezaecia.com.br',
                'cep' => '01310-100',
                'logradouro' => 'Avenida Paulista',
                'numero' => '1578',
                'complemento' => 'Sala 201',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['São Paulo', 'Guarulhos', 'Campinas', 'Santos', 'São Bernardo do Campo'],
                'state' => 'SP'
            ],
            [
                'company_name' => 'Cosméticos Rio Distribuidora S.A.',
                'trade_name' => 'Cosméticos Rio',
                'cnpj' => '23.456.789/0001-01',
                'email' => 'vendas@cosmeticosrio.com.br',
                'phone' => '(21) 2345-6789',
                'phone2' => null,
                'whatsapp' => '(21) 99876-5432',
                'website' => 'https://www.cosmeticosrio.com.br',
                'cep' => '20040-020',
                'logradouro' => 'Avenida Rio Branco',
                'numero' => '156',
                'complemento' => 'Loja 3',
                'bairro' => 'Centro',
                'cidade' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Rio de Janeiro', 'Niterói', 'Duque de Caxias', 'Nova Iguaçu'],
                'state' => 'RJ'
            ],
            [
                'company_name' => 'Minas Beleza Comércio de Cosméticos Ltda',
                'trade_name' => 'Minas Beleza',
                'cnpj' => '34.567.890/0001-12',
                'email' => 'contato@minasbeleza.com.br',
                'phone' => '(31) 3234-5678',
                'phone2' => '(31) 3234-5679',
                'whatsapp' => '(31) 99987-6543',
                'website' => null,
                'cep' => '30130-010',
                'logradouro' => 'Avenida Afonso Pena',
                'numero' => '867',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'Belo Horizonte',
                'estado' => 'MG',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Belo Horizonte', 'Contagem', 'Betim', 'Uberlândia'],
                'state' => 'MG'
            ],
            [
                'company_name' => 'Sul Cosmetics Distribuidora Ltda',
                'trade_name' => 'Sul Cosmetics',
                'cnpj' => '45.678.901/0001-23',
                'email' => 'comercial@sulcosmetics.com.br',
                'phone' => '(51) 3345-6789',
                'phone2' => '(51) 3345-6790',
                'whatsapp' => '(51) 99876-5431',
                'website' => 'https://www.sulcosmetics.com.br',
                'cep' => '90010-270',
                'logradouro' => 'Rua dos Andradas',
                'numero' => '1234',
                'complemento' => 'Andar 5',
                'bairro' => 'Centro Histórico',
                'cidade' => 'Porto Alegre',
                'estado' => 'RS',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Porto Alegre', 'Canoas', 'Caxias do Sul', 'Pelotas'],
                'state' => 'RS'
            ],
            [
                'company_name' => 'Nordeste Beauty Distribuidora ME',
                'trade_name' => 'Nordeste Beauty',
                'cnpj' => '56.789.012/0001-34',
                'email' => 'sac@nordestebeauty.com.br',
                'phone' => '(81) 3456-7890',
                'phone2' => null,
                'whatsapp' => '(81) 98765-4320',
                'website' => 'https://www.nordestebeauty.com.br',
                'cep' => '50010-000',
                'logradouro' => 'Avenida Conde da Boa Vista',
                'numero' => '500',
                'complemento' => 'Loja 12',
                'bairro' => 'Boa Vista',
                'cidade' => 'Recife',
                'estado' => 'PE',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Recife', 'Olinda', 'Jaboatão dos Guararapes', 'Caruaru'],
                'state' => 'PE'
            ],
            [
                'company_name' => 'Bahia Cosméticos e Perfumaria Ltda',
                'trade_name' => 'Bahia Cosméticos',
                'cnpj' => '67.890.123/0001-45',
                'email' => 'vendas@bahiacosmeticos.com.br',
                'phone' => '(71) 3567-8901',
                'phone2' => '(71) 3567-8902',
                'whatsapp' => '(71) 99654-3210',
                'website' => null,
                'cep' => '40020-000',
                'logradouro' => 'Avenida Sete de Setembro',
                'numero' => '789',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'Salvador',
                'estado' => 'BA',
                'status' => 'pending',
                'email_verified_at' => now(),
                'cities' => ['Salvador', 'Feira de Santana', 'Vitória da Conquista'],
                'state' => 'BA'
            ],
            [
                'company_name' => 'Brasília Beauty Center Ltda',
                'trade_name' => 'Brasília Beauty',
                'cnpj' => '78.901.234/0001-56',
                'email' => 'contato@brasiliabeauty.com.br',
                'phone' => '(61) 3678-9012',
                'phone2' => null,
                'whatsapp' => '(61) 99543-2109',
                'website' => 'https://www.brasiliabeauty.com.br',
                'cep' => '70040-020',
                'logradouro' => 'SCS Quadra 1 Bloco A',
                'numero' => 's/n',
                'complemento' => 'Ed. Central - Loja 45',
                'bairro' => 'Asa Sul',
                'cidade' => 'Brasília',
                'estado' => 'DF',
                'status' => 'pending',
                'email_verified_at' => null,
                'cities' => ['Brasília'],
                'state' => 'DF'
            ],
            [
                'company_name' => 'Amazonas Cosméticos Naturais Ltda',
                'trade_name' => 'Amazonas Naturais',
                'cnpj' => '89.012.345/0001-67',
                'email' => 'vendas@amazonasnaturais.com.br',
                'phone' => '(92) 3789-0123',
                'phone2' => '(92) 3789-0124',
                'whatsapp' => '(92) 99432-1098',
                'website' => 'https://www.amazonasnaturais.com.br',
                'cep' => '69010-000',
                'logradouro' => 'Avenida Eduardo Ribeiro',
                'numero' => '520',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'Manaus',
                'estado' => 'AM',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Manaus'],
                'state' => 'AM'
            ],
            [
                'company_name' => 'Ceará Beauty Distribution Ltda',
                'trade_name' => 'Ceará Beauty',
                'cnpj' => '90.123.456/0001-78',
                'email' => 'comercial@cearabeauty.com.br',
                'phone' => '(85) 3890-1234',
                'phone2' => null,
                'whatsapp' => '(85) 99321-0987',
                'website' => null,
                'cep' => '60010-000',
                'logradouro' => 'Rua Major Facundo',
                'numero' => '234',
                'complemento' => 'Sala 302',
                'bairro' => 'Centro',
                'cidade' => 'Fortaleza',
                'estado' => 'CE',
                'status' => 'rejected',
                'email_verified_at' => now(),
                'cities' => ['Fortaleza', 'Caucaia'],
                'state' => 'CE'
            ],
            [
                'company_name' => 'Goiás Perfumaria e Cosméticos S.A.',
                'trade_name' => 'Goiás Perfumaria',
                'cnpj' => '01.234.567/0001-89',
                'email' => 'sac@goiasperfumaria.com.br',
                'phone' => '(62) 3901-2345',
                'phone2' => '(62) 3901-2346',
                'whatsapp' => '(62) 99210-9876',
                'website' => 'https://www.goiasperfumaria.com.br',
                'cep' => '74010-010',
                'logradouro' => 'Avenida Goiás',
                'numero' => '678',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'Goiânia',
                'estado' => 'GO',
                'status' => 'approved',
                'email_verified_at' => now(),
                'cities' => ['Goiânia', 'Aparecida de Goiânia', 'Anápolis'],
                'state' => 'GO'
            ],
        ];

        foreach ($distributors as $distributorData) {
            // Separar dados das cidades
            $cityNames = $distributorData['cities'];
            $stateCode = $distributorData['state'];
            unset($distributorData['cities'], $distributorData['state']);

            // Criar distribuidor
            $distributor = Distributor::create($distributorData);

            // Buscar e vincular cidades
            $cities = City::whereIn('name', $cityNames)
                ->whereHas('state', function($query) use ($stateCode) {
                    $query->where('uf', $stateCode);
                })
                ->pluck('id')
                ->toArray();

            if (!empty($cities)) {
                $distributor->cities()->attach($cities);
            }
        }
    }
}
