<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'lácteos y huevos',
                'description' => 'productos lácteos y huevos frescos, como leche, queso, yogur y huevos.'
            ],
            [
                'name' => 'cereales y granos',
                'description' => 'harinas, avena, arroz, quinoa y otros granos y cereales.'
            ],
            [
                'name' => 'frutos secos',
                'description' => 'almendras, nueces, pistachos y mezclas de frutos secos y semillas.'
            ],
            [
                'name' => 'bebidas',
                'description' => 'bebidas no alcohólicas: jugos, refrescos, agua y bebidas isotónicas.'
            ],
            [
                'name' => 'enlatados y conservas',
                'description' => 'alimentos enlatados y en conserva: verduras, sopas, atún y salsas.'
            ],
            [
                'name' => 'salsas y condimentos',
                'description' => 'salsas, aderezos y condimentos para cocinar y acompañar platos.'
            ],
            [
                'name' => 'aceites y vinagres',
                'description' => 'aceites vegetales y diferentes tipos de vinagre.'
            ],
            [
                'name' => 'azúcares',
                'description' => 'azúcar y endulzantes naturales y artificiales.'
            ],
            [
                'name' => 'dulces',
                'description' => 'golosinas azucaradas.'
            ],
            [
                'name' => 'snacks',
                'description' => 'frituras.'
            ],
            [
                'name' => 'limpieza del hogar',
                'description' => 'detergentes, desinfectantes y utensilios para la limpieza del hogar.'
            ],
            [
                'name' => 'higiene personal',
                'description' => 'productos de cuidado personal: jabón, champú, pasta dental y desodorante.'
            ],
            [
                'name' => 'mascotas',
                'description' => 'alimentos y accesorios para perros, gatos y otras mascotas.'
            ],
            [
                'name' => 'bebés',
                'description' => 'productos para bebés: pañales, alimentos infantiles y productos de cuidado.'
            ],
            [
                'name' => 'bebidas alcohólicas',
                'description' => 'vinos, cervezas y licores destinados al consumo adulto.'
            ],
            [
                'name' => 'especias y hierbas',
                'description' => 'especias, hierbas secas y mezclas para sazonar todo tipo de platos.'
            ]
        ];
    }
}
