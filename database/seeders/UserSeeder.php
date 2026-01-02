<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        // Configurações
        $totalUsers = 150000; // 150 mil
        $batchSize = 1000;    // Insere de 1000 em 1000 para não estourar a memória

        $this->command->info("Iniciando a criação de {$totalUsers} usuários...");

        $progressBar = $this->command->getOutput()->createProgressBar($totalUsers);

        for ($i = 0; $i < $totalUsers; $i += $batchSize) {
            $data = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $data[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->userName . $i . $j . '@example.com',
                    'google_id' => Str::random(21),
                    'google_token' => Str::random(50),
                    'avatar' => 'https://i.pravatar.cc/150?u=' . md5($faker->unique()->userName . $i . $j),
                    'cpf' => $faker->cpf(false),
                    'birth_date' => $faker->date('Y-m-d', '-18 years'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('users')->insert($data);

            $progressBar->advance($batchSize);
        }

        $progressBar->finish();
        $this->command->info("\nSeeder finalizado com sucesso!");
    }
}
