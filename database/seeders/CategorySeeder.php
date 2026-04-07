<?php

namespace Database\Seeders;

use App\Models\Category;
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
            'lácteos, huevos y refrigerados' => 'productos lácteos y huevos frescos, como leche, queso, yogur y huevos.',
            'cereales y granos' => 'harinas, avena, arroz, quinoa y otros granos y cereales.',
            'enlatados y conservas' => 'alimentos enlatados y en conserva: verduras, sopas, atún y salsas.',
            'aceites y vinagres' => 'aceites vegetales y diferentes tipos de vinagre.',
            'especias y hierbas' => 'especias, hierbas secas y mezclas para sazonar todo tipo de platos.',
            'galletas y dulces' => 'productos dulces como galletas, chocolates, caramelos y golosinas.',
            'snacks salados' => 'botanas saladas como papas fritas, frituras y crackers.',
            'bebidas' => 'bebidas como refrescos, jugos, café y bebidas en polvo.',
            'abarrotes básicos' => 'productos esenciales de despensa como arroz, azúcar, sal, café y mayonesa.',
            'condimentos y salsas' => 'salsas, especias, aderezos y productos para sazonar alimentos.',
            'panadería' => 'pan, bollería y productos de pan dulce o empaquetado.',
            'cuidado personal' => 'productos de higiene personal como shampoo, jabón, pasta dental y desodorantes.',
            'bebés' => 'productos para bebés: pañales, alimentos infantiles y productos de cuidado.',
            'papel y desechables' => 'productos de papel y uso desechable como servilletas, papel higiénico y bolsas.',
            'limpieza del hogar' => 'productos para la limpieza como detergentes, cloro y limpiadores.',
            'hogar' => 'utilidades para el hogar.',
            'encendedores y cerillos' => 'encendedores, cerillos y artículos para encender fuego.',
            'tabaco' => 'productos de tabaco como cigarros.',
            'bebidas alcohólicas' => 'vinos, cervezas y licores destinados al consumo adulto.',
            'mascotas' => 'alimentos y accesorios para perros, gatos y otras mascotas.',
            'frutos secos' => 'almendras, nueces, pistachos y mezclas de frutos secos y semillas.',
            'papelería' => 'útiles escolares.',
            'concentrados y esencias' => 'para preparación de bebidas y alimentos.',
            'sopas' => 'sopas instantáneas y precocidas.',
            'farmacia' => 'salud.',
            'otros' => 'productos varios.',
        ];

        foreach ($categories as $category => $description) {
            Category::create([
                'name' => $category,
                'description' => $description
            ]);
        }
    }
}
