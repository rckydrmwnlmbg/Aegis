<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HazardObservation;
use Illuminate\Support\Str;

class HazardObservationFactory extends Factory
{
    protected $model = HazardObservation::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'tenant_id' => Str::uuid()->toString(),
            'status' => 'draft',
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'risk_score' => $this->faker->randomFloat(2, 0, 100),
            'observed_at' => now(),
            'observed_by' => Str::uuid()->toString(),
        ];
    }
}
