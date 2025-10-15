<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $measureUnits = [
            ['name' => 'caja', 'abbreviation' => 'cja'],
            ['name' => 'bulto', 'abbreviation' => 'bul'],
            ['name' => 'exhibidor', 'abbreviation' => 'exi'],
            ['name' => 'unidad', 'abbreviation' => 'uni'],
            ['name' => 'pieza', 'abbreviation' => 'pz'],
            ['name' => 'paquete', 'abbreviation' => 'paq'],
            ['name' => 'docena', 'abbreviation' => 'doc'],
            ['name' => 'botella', 'abbreviation' => 'bot'],
            ['name' => 'lata', 'abbreviation' => 'lat'],
            ['name' => 'galón', 'abbreviation' => 'gal'],
            ['name' => 'sobre', 'abbreviation' => 'sob'],
            ['name' => 'tarro', 'abbreviation' => 'tar'],
            ['name' => 'bolsa', 'abbreviation' => 'bol'],
            ['name' => 'rollo', 'abbreviation' => 'rol'],
            ['name' => 'tubo', 'abbreviation' => 'tub'],
            ['name' => 'frasco', 'abbreviation' => 'frs'],
            ['name' => 'cajón', 'abbreviation' => 'cjn'],
            ['name' => 'barril', 'abbreviation' => 'brr'],
            ['name' => 'cubeta', 'abbreviation' => 'cub'],
            ['name' => 'kilogramo', 'abbreviation' => 'kg'],
            ['name' => 'gramo', 'abbreviation' => 'g'],
            ['name' => 'litro', 'abbreviation' => 'l'],
            ['name' => 'mililitro', 'abbreviation' => 'ml'],
            ['name' => 'libra', 'abbreviation' => 'lb'],
            ['name' => 'onza', 'abbreviation' => 'oz'],
        ];

        foreach ($measureUnits as $unit) {
            MeasureUnit::create($unit);
        }
    }
}
