<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_place_order_for_single_seller(): void
    {
        $category = Category::create([
            'name' => 'Agroalimentaire',
            'slug' => 'agroalimentaire',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Huile de palme',
            'slug' => 'huile-de-palme',
            'description' => 'Huile locale de qualité.',
            'price' => 18000,
            'stock' => 20,
            'currency' => 'XOF',
            'is_active' => true,
        ]);

        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->postJson('/orders', [
            'shipping_address' => 'Cotonou, Bénin',
            'payment_method' => 'card',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('total_amount', '36000.00');
        $this->assertDatabaseHas('orders', ['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);
    }

    public function test_buyer_cannot_place_order_when_quantity_exceeds_stock(): void
    {
        $category = Category::create([
            'name' => 'Agroalimentaire',
            'slug' => 'agroalimentaire',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Miel local',
            'slug' => 'miel-local',
            'description' => 'Miel de qualité du Bénin.',
            'price' => 12000,
            'stock' => 1,
            'currency' => 'XOF',
            'is_active' => true,
        ]);

        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->postJson('/orders', [
            'shipping_address' => 'Cotonou, Bénin',
            'payment_method' => 'card',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Stock insuffisant pour le produit Miel local.');
    }

    public function test_buyer_can_place_order_with_duplicate_product_lines(): void
    {
        $category = Category::create([
            'name' => 'Agroalimentaire',
            'slug' => 'agroalimentaire',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Farine locale',
            'slug' => 'farine-locale',
            'description' => 'Farine béninoise de qualité.',
            'price' => 12000,
            'stock' => 10,
            'currency' => 'XOF',
            'is_active' => true,
        ]);

        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->postJson('/orders', [
            'shipping_address' => 'Cotonou, Bénin',
            'payment_method' => 'card',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('total_amount', '60000.00');
        $this->assertDatabaseHas('orders', ['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 5]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
    }

    public function test_only_buyers_can_place_orders(): void
    {
        $category = Category::create([
            'name' => 'Agroalimentaire',
            'slug' => 'agroalimentaire',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Café béninois',
            'slug' => 'cafe-beninois',
            'description' => 'Café local torréfié.',
            'price' => 15000,
            'stock' => 10,
            'currency' => 'XOF',
            'is_active' => true,
        ]);

        $sellerBuyer = User::factory()->create(['role' => 'seller', 'seller_status' => 'approved']);

        $response = $this->actingAs($sellerBuyer)->postJson('/orders', [
            'shipping_address' => 'Cotonou, Bénin',
            'payment_method' => 'card',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(403);
        $response->assertJsonPath('message', 'Seulement les acheteurs peuvent passer une commande.');
    }

    public function test_buyer_cannot_place_order_for_inactive_product(): void
    {
        $category = Category::create([
            'name' => 'Agroalimentaire',
            'slug' => 'agroalimentaire',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Poivre local',
            'slug' => 'poivre-local',
            'description' => 'Poivre local du Bénin.',
            'price' => 9000,
            'stock' => 10,
            'currency' => 'XOF',
            'is_active' => false,
        ]);

        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->postJson('/orders', [
            'shipping_address' => 'Cotonou, Bénin',
            'payment_method' => 'card',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Un ou plusieurs produits sont introuvables ou inactifs.');
    }
}
