<?php

namespace Database\Factories;

use App\Models\CorrectiveAction;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorrectiveActionFactory extends Factory
{
    protected $model = CorrectiveAction::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'capa_number' => $this->faker->unique()->numerify('CAPA-####'),
            'action_type' => 'corrective',
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => 'open',
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
