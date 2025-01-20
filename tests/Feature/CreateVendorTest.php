<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVendorTest extends TestCase
{

    use RefreshDatabase;

    #[test]
    public function livewire_component_is_accessible_on_route()
    {

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('create-vendor'));

        $response->assertStatus(200);

        $this->get(route('create-vendor'))
        ->assertStatus(200)
            ->assertSeeLivewire('create-vendor-component');
    }

    #[test]
    public function it_saves_vendor_to_database()
    {

        Livewire::test('create-vendor-component')
        ->set('vendor_name', 'test_vendor')
            ->call('createVendor');


        $this->assertDatabaseHas('vendors', [
            'name' => 'test_vendor',
        ]);
    }
}
