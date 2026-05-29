<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Incident;
use Illuminate\Support\Str;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'tenant_id' => Str::uuid()->toString(),
            'status' => 'draft',
            'title' => $this->faker->sentence(),
            'summary' => $this->faker->paragraph(),
            'occurred_at' => now(),
            'reported_at' => now(),
            'created_by' => Str::uuid()->toString(),
        ];
    }
}
