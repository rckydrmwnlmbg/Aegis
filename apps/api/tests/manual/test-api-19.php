<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\AppUser;
use Illuminate\Support\Str;

$tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => Str::random(10), 'name' => 'T']);
$user = AppUser::factory()->create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenant->id, 'email' => Str::random(10).'@e.com', 'password' => 'x']);
Laravel\Sanctum\Sanctum::actingAs($user);

$uuid = Str::uuid()->toString();

$payload = ['id' => $uuid, 'notes' => 'hi'];

$response1 = app()->handle(\Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload));
Laravel\Sanctum\Sanctum::actingAs($user);
$response2 = app()->handle(\Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload));

echo \App\Models\AiRun::count() . "\n";
