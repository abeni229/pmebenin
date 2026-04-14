<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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
        $admin = User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'seller_status' => 'approved',
        ]);

        $seller = User::factory()->create([
            'name' => 'Artisan Béninois',
            'email' => 'seller@example.com',
            'password' => 'password',
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $artisanat = Category::create(['name' => 'Artisanat', 'slug' => 'artisanat', 'description' => 'Produits artisanaux du Bénin.']);
        $textile = Category::create(['name' => 'Textile', 'slug' => 'textile', 'description' => 'Textiles locaux et vêtements traditionnels.']);
        $agro = Category::create(['name' => 'Agroalimentaire', 'slug' => 'agroalimentaire', 'description' => 'Produits agroalimentaires frais et transformés.']);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $textile->id,
            'name' => 'Pagne wax artisanal',
            'slug' => 'pagne-wax-artisanal',
            'description' => 'Un pagne wax fabriqué à la main, idéal pour une tenue traditionnelle ou une décoration authentique.',
            'price' => 18500,
            'stock' => 16,
            'currency' => 'XOF',
            'image' => 'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $agro->id,
            'name' => 'Huile de palme premium',
            'slug' => 'huile-de-palme-premium',
            'description' => 'Huile de palme locale soigneusement filtrée, parfaite pour la cuisine traditionnelle béninoise.',
            'price' => 12500,
            'stock' => 24,
            'currency' => 'XOF',
            'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $artisanat->id,
            'name' => 'Objet déco en bois',
            'slug' => 'objet-deco-en-bois',
            'description' => 'Une pièce déco artisanale en bois, idéale pour un intérieur naturel et chaleureux.',
            'price' => 24900,
            'stock' => 9,
            'currency' => 'XOF',
            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
        ]);
    }
}
