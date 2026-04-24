<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@construtec.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Product::create([
            'name' => 'Taladro Inalámbrico 20V',
            'description' => 'Taladro percutor profesional con 2 baterías y cargador rápido. Ideal para trabajos pesados, diseño ergonómico de alta resistencia.',
            'price' => 125.50,
            'image_url' => 'images/drill.png',
            'stock' => 15
        ]);

        Product::create([
            'name' => 'Martillo Ergonómico',
            'description' => 'Martillo de acero forjado con mango antideslizante para mejor agarre. Gran resistencia y durabilidad.',
            'price' => 18.75,
            'image_url' => 'images/hammer.png',
            'stock' => 40
        ]);

        Product::create([
            'name' => 'Sierra Circular 1800W',
            'description' => 'Sierra circular de alta potencia para cortes precisos en madera. Incluye hoja de tungsteno.',
            'price' => 95.00,
            'image_url' => 'images/circular_saw.png',
            'stock' => 12
        ]);

        Product::create([
            'name' => 'Set de Destornilladores',
            'description' => 'Juego de 12 destornilladores de precisión magnéticos. Diferentes tamaños y tipos de puntas para cualquier necesidad.',
            'price' => 24.99,
            'image_url' => 'images/screwdriver.png',
            'stock' => 30
        ]);
    }
}
