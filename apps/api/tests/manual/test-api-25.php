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

$uuid = Str::uuid()->toString();
$payload = ['id' => $uuid, 'notes' => 'hi'];

$token = $user->createToken('test')->plainTextToken;

$response1 = app()->handle(\Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload, [], [], ['HTTP_AUTHORIZATION' => "Bearer $token", 'HTTP_ACCEPT' => 'application/json']));
$response2 = app()->handle(\Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload, [], [], ['HTTP_AUTHORIZATION' => "Bearer $token", 'HTTP_ACCEPT' => 'application/json']));

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count: " . count($aiRuns) . "\n";
foreach($aiRuns as $run) {
    echo $run->id . " - tenant: " . $run->tenant_id . "\n";
}
