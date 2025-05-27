<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAssistanceRecipient>
 */
class SocialAssistanceRecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'reason' => $this->faker->sentence(),
            'bank' => $this->faker->randomElement(['bri', 'bni', 'mandiri', 'bca']),
            'account_number' => (string) $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'proof' => $this->faker->url(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
