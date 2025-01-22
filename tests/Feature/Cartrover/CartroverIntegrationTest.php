<?php

namespace Tests\Feature\Cartrover;

use Tests\TestCase;
use App\Models\Vendor;
use App\Models\CartroverIntegration;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartroverIntegrationTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function test_cartrover_integration_route()
    {
        // Kreiranje lažnih podataka
        $vendor = Vendor::create(['name' => 'test-vendor']);
        $authValue = 'test-auth';

        // Dodajemo povezanu instancu CartroverIntegration
        $cartroverIntegration = CartroverIntegration::factory()->create([
            'vendor_id' => $vendor->id,
            'auth' => $authValue,
        ]);

        // // Proveravamo da li je vendor pronađen
        // $foundVendor = Vendor::where('name', $vendor->name)->first();
        // if ($vendor->name !== $foundVendor->name) {
        //     $response->assertStatus(400)
        //         ->assertJson(['error' => 'no vendor']);
        // }

        // // Proveravamo povezanost sa CartroverIntegration
        // $relatedIntegration = $foundVendor->cartroverIntegration()->where('auth', $authValue)->first();
        // $this->assertInstanceOf(CartroverIntegration::class, $relatedIntegration, "CartroverIntegration not found for vendor");

        // // Proveravamo vrednost auth
        // if ($authValue !== $relatedIntegration->auth) {
        //     $response->assertStatus(400)
        //         ->assertJson(['error' => 'wrong auth']);
        // } else {
        //     $response->assertStatus(200);
        // }

        $response = $this->post("api/integration/cartrover/{$vendor->name}/{$authValue}");
        // Parsiraj odgovor i proveri instancu
        $responseData = $response->decodeResponseJson();

        $this->assertEquals(
            $cartroverIntegration->id,
            $responseData['id'],
            'Returned CartroverIntegration does not match the expected instance.'
        );
    }

}
