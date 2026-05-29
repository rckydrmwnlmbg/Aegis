<?php
require 'apps/api/vendor/autoload.php';
$app = require_once 'apps/api/bootstrap/app.php';
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

$request1 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request1->headers->set('Authorization', "Bearer $token");
$response1 = app()->handle($request1);

echo "R1: " . $response1->getStatusCode() . "\n";

$request2 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request2->headers->set('Authorization', "Bearer $token");
$response2 = app()->handle($request2);

echo "R2: " . $response2->getStatusCode() . "\n";

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count: " . count($aiRuns) . "\n";
