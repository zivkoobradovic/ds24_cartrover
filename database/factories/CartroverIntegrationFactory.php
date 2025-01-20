<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartroverIntegration>
 */
class CartroverIntegrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Integration', // Random integration name
            'vendor_id' => Vendor::factory(), // Associate with a Vendor model
            'ipn_pass' => $this->faker->password(8), // Random password
            'auth' => base64_encode($this->faker->word . ':' . $this->faker->password), // Random base64 encoded auth
            'http_header' => $this->faker->word, // Random string for HTTP header
            'ds24_api_key' => $this->faker->uuid, // Random UUID for API key
            'cr_api_user' => $this->faker->userName, // Random username
            'cr_api_pass' => $this->faker->password, // Random password
            'products' => json_encode([$this->faker->randomNumber(), $this->faker->randomNumber()]), // Array of random product IDs
            'ipn_url' => $this->faker->url, // Random URL
        ];
    }
}
