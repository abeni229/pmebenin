<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'seller_status' => 'approved',
        ]);

        Category::create(['name' => 'Artisanat', 'slug' => 'artisanat', 'description' => 'Produits artisanaux du Bénin.']);
        Category::create(['name' => 'Textile', 'slug' => 'textile', 'description' => 'Textiles locaux et vêtements traditionnels.']);
        Category::create(['name' => 'Agroalimentaire', 'slug' => 'agroalimentaire', 'description' => 'Produits agroalimentaires frais et transformés.']);
    }
}
