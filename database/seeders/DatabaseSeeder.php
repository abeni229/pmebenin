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
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrateur',
            'password' => 'password',
            'role' => 'admin',
            'seller_status' => 'approved',
        ]);

        $seller1 = User::updateOrCreate([
            'email' => 'seller@example.com',
        ], [
            'name' => 'Artisan Béninois',
            'password' => 'password',
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $seller2 = User::updateOrCreate([
            'email' => 'textile@example.com',
        ], [
            'name' => 'Textile Expert',
            'password' => 'password',
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $buyer = User::updateOrCreate([
            'email' => 'buyer@example.com',
        ], [
            'name' => 'Client Test',
            'password' => 'password',
            'role' => 'buyer',
            'seller_status' => 'approved',
        ]);

        $artisan = User::updateOrCreate([
            'email' => 'buyer2@example.com',
        ], [
            'name' => 'Acheteur Local',
            'password' => 'password',
            'role' => 'buyer',
            'seller_status' => 'approved',
        ]);

        $artisanat = Category::firstOrCreate([
            'slug' => 'artisanat',
        ], [
            'name' => 'Artisanat',
            'description' => 'Produits artisanaux du Bénin.',
        ]);

        $textile = Category::firstOrCreate([
            'slug' => 'textile',
        ], [
            'name' => 'Textile',
            'description' => 'Textiles locaux et vêtements traditionnels.',
        ]);

        $agro = Category::firstOrCreate([
            'slug' => 'agroalimentaire',
        ], [
            'name' => 'Agroalimentaire',
            'description' => 'Produits agroalimentaires frais et transformés.',
        ]);

        $products = [
            [
                'seller_id' => $seller1->id,
                'category_id' => $textile->id,
                'name' => 'Pagne wax artisanal',
                'slug' => 'pagne-wax-artisanal',
                'description' => 'Un pagne wax fabriqué à la main, idéal pour une tenue traditionnelle ou une décoration authentique.',
                'price' => 18500,
                'stock' => 16,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
            [
                'seller_id' => $seller1->id,
                'category_id' => $agro->id,
                'name' => 'Huile de palme premium',
                'slug' => 'huile-de-palme-premium',
                'description' => 'Huile de palme locale soigneusement filtrée, parfaite pour la cuisine traditionnelle béninoise.',
                'price' => 12500,
                'stock' => 24,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
            [
                'seller_id' => $seller1->id,
                'category_id' => $artisanat->id,
                'name' => 'Objet déco en bois',
                'slug' => 'objet-deco-en-bois',
                'description' => 'Une pièce déco artisanale en bois, idéale pour un intérieur naturel et chaleureux.',
                'price' => 24900,
                'stock' => 9,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
            [
                'seller_id' => $seller2->id,
                'category_id' => $textile->id,
                'name' => 'Chemise en coton brodée',
                'slug' => 'chemise-en-coton-brodee',
                'description' => 'Chemise légère en coton brodée à la main, idéale pour un style chic et confortable.',
                'price' => 21000,
                'stock' => 12,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
            [
                'seller_id' => $seller2->id,
                'category_id' => $artisanat->id,
                'name' => 'Sac en raphia tissé',
                'slug' => 'sac-en-raphia-tisse',
                'description' => 'Sac artisanal en raphia, robuste et élégant pour un usage quotidien.',
                'price' => 19500,
                'stock' => 20,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
            [
                'seller_id' => $seller2->id,
                'category_id' => $agro->id,
                'name' => 'Confiture de mangue',
                'slug' => 'confiture-de-mangue',
                'description' => 'Confiture artisanale de mangue, sucrée et parfumée pour accompagner pain et gâteaux.',
                'price' => 9800,
                'stock' => 30,
                'currency' => 'XOF',
                'image' => 'https://images.unsplash.com/photo-1510627498534-cf7e9002facc?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate([
                'slug' => $productData['slug'],
            ], $productData);
        }
    }
}
