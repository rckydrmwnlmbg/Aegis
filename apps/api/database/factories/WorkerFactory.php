<?php

namespace Database\Factories;

use App\Models\Worker;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->name(),
        ];
    }
}
