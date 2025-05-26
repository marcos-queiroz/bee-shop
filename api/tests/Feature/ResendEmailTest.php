<?php

namespace Tests\Feature;

use App\Mail\SellerDailyReportMail;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_resend_email_if_sales_exist()
    {
        Mail::fake();
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create([
            'email' => 'vendedor@example.com',
        ]);

        Sale::factory()->create([
            'seller_id' => $seller->id,
            'amount' => 100,
            'commission' => 8.5,
            'sale_date' => now()->toDateString(),
        ]);

        $response = $this->postJson("/api/sellers/{$seller->id}/resend-email", [
            'date' => now()->toDateString(),
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'E-mail reenviado com sucesso.',
            ]);

        Mail::assertQueued(SellerDailyReportMail::class);
    }

    public function test_cannot_resend_email_if_no_sales_found()
    {
        Mail::fake();
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        $response = $this->postJson("/api/sellers/{$seller->id}/resend-email", [
            'date' => now()->toDateString(),
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Nenhuma venda encontrada para essa data.',
            ]);

        Mail::assertNothingQueued();
    }
}
