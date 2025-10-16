<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Maria Gamesa 1/22 rollos/180g',
                'stock' => 0,
                'cost_price' => 250.00,
                'first_sale_percentage' => 0.03,
                'second_sale_percentage' => 0.05,
                'third_sale_percentage' => 0.08,
            ],
            [
                'name' => 'Crackets 1/20 y 22 rollos/135g',
                'stock' => 50,
                'cost_price' => 255.00,
                'first_sale_percentage' => 0.03,
                'second_sale_percentage' => 0.05,
                'third_sale_percentage' => 0.08,
            ],
            [
                'name' => 'Marias Cuetara 1/20 rollos/200g',
                'stock' => 200,
            ],
            [
                'name' => 'Oro Cuetara 1/20 rollos/200g',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
