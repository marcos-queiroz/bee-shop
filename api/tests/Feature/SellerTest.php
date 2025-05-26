<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_seller()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/sellers', [
            'name' => 'Vendedor Teste',
            'email' => 'vendedor@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Vendedor Teste']);
    }

    public function test_can_list_sellers()
    {
        Sanctum::actingAs(User::factory()->create());

        Seller::factory()->count(3)->create();

        $response = $this->getJson('/api/sellers');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_can_show_a_seller()
    {
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        $response = $this->getJson("/api/sellers/{$seller->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $seller->id,
                'name' => $seller->name,
                'email' => $seller->email,
            ]);
    }

    public function test_authenticated_user_can_update_seller()
    {
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        $response = $this->putJson("/api/sellers/{$seller->id}", [
            'name' => 'Nome Atualizado',
            'email' => $seller->email,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Nome Atualizado']);
    }

    public function test_authenticated_user_can_delete_seller()
    {
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        $response = $this->deleteJson("/api/sellers/{$seller->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
    }
}
