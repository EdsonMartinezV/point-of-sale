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
            'caja' => 'cja',
            'cajetilla' => 'cjt',
            'bulto' => 'bul',
            'exhibidor' => 'exi',
            'unidad' => 'uni',
            'pieza' => 'pz',
            'paquete' => 'paq',
            'docena' => 'doc',
            'botella' => 'bot',
            'lata' => 'lat',
            'galón' => 'gal',
            'sobre' => 'sob',
            'tarro' => 'tar',
            'bolsa' => 'bol',
            'rollo' => 'rol',
            'tubo' => 'tub',
            'frasco' => 'frs',
            'barril' => 'brr',
            'cubeta' => 'cub',
            'kilogramo' => 'kg',
            'gramo' => 'g',
            'litro' => 'l',
            'mililitro' => 'ml',
            'libra' => 'lb',
            'onza' => 'oz',
            'charola' => 'chr',
            'cono' => 'con',
            'tira' => 'tra',
            'vaso' => 'vso',
            'tazon' => 'tzn',
            'estuche' => 'est',
            'six' => 'six',
        ];

        foreach ($measureUnits as $name => $abbreviation) {
            MeasureUnit::create([
                'name' => $name,
                'abbreviation' => $abbreviation
            ]);
        }
    }
}
