<?php

namespace Database\Factories;

use App\Models\PermitToWork;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermitToWorkFactory extends Factory
{
    protected $model = PermitToWork::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'permit_number' => $this->faker->unique()->numerify('PTW-####'),
            'status' => 'active',
            'title' => $this->faker->sentence(),
            'valid_from' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'valid_until' => $this->faker->dateTimeBetween('now', '+1 week'),
        ];
    }
}
