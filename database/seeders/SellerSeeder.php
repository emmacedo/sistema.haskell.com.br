<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Distributor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributors = Distributor::all();

        $sellerNames = [
            'Carlos Silva',
            'Ana Paula Santos',
            'Roberto Oliveira',
            'Mariana Costa',
            'Fernando Alves',
            'Juliana Ferreira',
            'Paulo Henrique',
            'Camila Rodrigues',
            'Lucas Martins',
            'Patricia Lima',
            'Ricardo Souza',
            'Beatriz Pereira',
            'André Carvalho',
            'Gabriela Nascimento',
            'Marcos Vieira',
            'Renata Dias',
            'Felipe Barros',
            'Larissa Gomes',
            'Thiago Ribeiro',
            'Amanda Araújo',
        ];

        $positions = [
            'Gerente de Vendas',
            'Representante Comercial',
            'Consultor de Vendas',
            'Executivo de Contas',
            'Coordenador Comercial',
            'Vendedor Senior',
            'Analista Comercial',
        ];

        $counter = 0;

        foreach ($distributors as $distributor) {
            // Cada distribuidor terá entre 2 a 4 vendedores
            $numberOfSellers = rand(2, 4);

            for ($i = 0; $i < $numberOfSellers; $i++) {
                $name = $sellerNames[$counter % count($sellerNames)];
                $firstName = explode(' ', $name)[0];
                $lastName = explode(' ', $name)[count(explode(' ', $name)) - 1];

                // Gerar telefone baseado no DDD do distribuidor
                $ddd = substr($distributor->phone, 1, 2);

                Seller::create([
                    'distributor_id' => $distributor->id,
                    'name' => $name,
                    'email' => strtolower($firstName . '.' . $lastName . '@' . str_replace(['https://www.', 'http://www.', '.com.br'], ['', '', ''], $distributor->website ?? $distributor->email)),
                    'phone' => "($ddd) " . rand(3000, 3999) . '-' . rand(1000, 9999),
                    'whatsapp' => "($ddd) 9" . rand(8000, 9999) . '-' . rand(1000, 9999),
                    'position' => $positions[array_rand($positions)],
                ]);

                $counter++;
            }
        }
    }
}
