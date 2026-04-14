<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_product_after_approval(): void
    {
        $category = Category::create([
            'name' => 'Artisanat',
            'slug' => 'artisanat',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        $response = $this->actingAs($seller)->postJson('/products', [
            'name' => 'Panier en raphia',
            'category_id' => $category->id,
            'description' => 'Panier artisanal du Bénin.',
            'price' => 12000,
            'stock' => 10,
            'currency' => 'XOF',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'Panier en raphia',
            'seller_id' => $seller->id,
        ]);
    }

    public function test_buyer_cannot_create_product(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->postJson('/products', [
            'name' => 'Produit interdit',
            'category_id' => 1,
            'price' => 1000,
            'stock' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_product_search_filters_by_category(): void
    {
        $category = Category::create([
            'name' => 'Textile',
            'slug' => 'textile',
        ]);

        $seller = User::factory()->create(['role' => 'seller', 'seller_status' => 'approved']);

        $this->actingAs($seller)->postJson('/products', [
            'name' => 'Tissu wax',
            'category_id' => $category->id,
            'description' => 'Textile local.',
            'price' => 8000,
            'stock' => 5,
        ]);

        $response = $this->getJson('/products?category=textile');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Tissu wax']);
    }

    public function test_only_admin_or_owner_can_update_product(): void
    {
        $category = Category::create([
            'name' => 'Textile',
            'slug' => 'textile',
        ]);

        $owner = User::factory()->create(['role' => 'seller', 'seller_status' => 'approved']);
        $otherSeller = User::factory()->create(['role' => 'seller', 'seller_status' => 'approved']);
        $admin = User::factory()->create(['role' => 'admin']);
        $buyer = User::factory()->create(['role' => 'buyer']);

        $product = $this->actingAs($owner)->postJson('/products', [
            'name' => 'Tissu wax',
            'category_id' => $category->id,
            'description' => 'Textile local.',
            'price' => 8000,
            'stock' => 5,
        ])->json();

        $response = $this->actingAs($buyer)->putJson("/products/{$product['id']}", ['price' => 9000]);
        $response->assertStatus(403);

        $response = $this->actingAs($otherSeller)->putJson("/products/{$product['id']}", ['price' => 9000]);
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->putJson("/products/{$product['id']}", ['price' => 9000]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', ['id' => $product['id'], 'price' => 9000]);
    }

    public function test_only_admin_or_owner_can_delete_product(): void
    {
        $category = Category::create([
            'name' => 'Artisanat',
            'slug' => 'artisanat',
        ]);

        $owner = User::factory()->create(['role' => 'seller', 'seller_status' => 'approved']);
        $admin = User::factory()->create(['role' => 'admin']);
        $buyer = User::factory()->create(['role' => 'buyer']);

        $product = $this->actingAs($owner)->postJson('/products', [
            'name' => 'Panier en raphia',
            'category_id' => $category->id,
            'description' => 'Panier artisanal du Bénin.',
            'price' => 12000,
            'stock' => 10,
            'currency' => 'XOF',
        ])->json();

        $response = $this->actingAs($buyer)->deleteJson("/products/{$product['id']}");
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->deleteJson("/products/{$product['id']}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product['id']]);
    }
}
