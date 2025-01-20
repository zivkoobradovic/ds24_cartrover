<?php

namespace Tests\Feature\Cartrover;

use App\Models\CartroverIntegration;
use Tests\TestCase;

use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use App\Services\Digistore24Service;
use Carbon\Factory;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCartroverIntegrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function auth_user_can_visit_create_cartrover_route(): void
    {
        $user = TestCase::authUser();
        $this->actingAs($user);

        $response = $this->get(route('create-cartrover-integration'));

        $response->assertStatus(200);
    }


    #[Test]
    public function auth_user_can_create_cartrover_integration()
    {
        $cartroverIntegration = CartroverIntegration::factory()->create();

        Livewire::test(\App\Livewire\CreateCartroverIntegrationComponent::class)
            ->set('ds24_api_key', $cartroverIntegration->ds24_api_key)
            ->set('ipn_pass', $cartroverIntegration->ipn_pass)
            ->set('http_header', $cartroverIntegration->http_header)
            ->set('cr_api_user', $cartroverIntegration->cr_api_user)
            ->set('cr_api_pass', $cartroverIntegration->cr_api_pass)
            ->set('products', json_decode($cartroverIntegration->products))
            ->set('name', $cartroverIntegration->name)
            ->set('ds24_user', $cartroverIntegration->vendor_id)
            ->call('saveIntegration');

            $this->assertDatabaseHas('cartrover_integrations', [
                'ds24_api_key' => $cartroverIntegration->ds24_api_key,
                'ipn_pass' => $cartroverIntegration->ipn_pass,
                'http_header' => $cartroverIntegration->http_header,
                'cr_api_user' => $cartroverIntegration->cr_api_user,
                'cr_api_pass' => $cartroverIntegration->cr_api_pass,
                'products' => json_encode($cartroverIntegration->products),
                'name' => $cartroverIntegration->name,
                'vendor_id' => $cartroverIntegration->vendor_id,
                'auth' => $cartroverIntegration->auth,
                'ipn_url' => $cartroverIntegration->ipn_url
            ]);
    }
}
