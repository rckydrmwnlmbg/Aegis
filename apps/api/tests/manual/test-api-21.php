<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\AppUser;
use Illuminate\Support\Str;

$tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => Str::random(10), 'name' => 'T']);
$user = AppUser::create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenant->id, 'email' => Str::random(10).'@e.com', 'password' => 'x']);
$token = $user->createToken('test')->plainTextToken;

$uuid = Str::uuid()->toString();
$payload = ['id' => $uuid, 'notes' => 'hi'];

echo "Token: $token\n";

$response1 = Illuminate\Support\Facades\Http::withToken($token)->post('http://127.0.0.1:8080/api/v1/sync/incidents', $payload);
$response2 = Illuminate\Support\Facades\Http::withToken($token)->post('http://127.0.0.1:8080/api/v1/sync/incidents', $payload);

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count: " . count($aiRuns) . "\n";
foreach($aiRuns as $run) {
    echo $run->id . " - tenant: " . $run->tenant_id . "\n";
}

$tenant2 = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => Str::random(10), 'name' => 'T2']);
$user2 = AppUser::create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenant2->id, 'email' => Str::random(10).'@e.com', 'password' => 'x']);
$token2 = $user2->createToken('test')->plainTextToken;

$response3 = Illuminate\Support\Facades\Http::withToken($token2)->post('http://127.0.0.1:8080/api/v1/sync/incidents', $payload);

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count: " . count($aiRuns) . "\n";
foreach($aiRuns as $run) {
    echo $run->id . " - tenant: " . $run->tenant_id . "\n";
}
