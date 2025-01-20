<?php

namespace Tests\Feature\Cartrover;

use App\Models\Digistore24Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateCartroverOrderTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function create_order_test(): void
    {
        $ds24Order = Digistore24Order::factory()->create();
    }
}
