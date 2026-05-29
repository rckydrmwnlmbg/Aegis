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

$request2 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request2->headers->set('Authorization', "Bearer $token");
$response2 = app()->handle($request2);

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count: " . count($aiRuns) . "\n";
foreach($aiRuns as $run) {
    echo $run->id . " - tenant: " . $run->tenant_id . "\n";
}

$tenantB = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => Str::random(10), 'name' => 'T2']);
$userB = AppUser::create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenantB->id, 'email' => Str::random(10).'@e.com', 'password' => 'x']);
$tokenB = $userB->createToken('testB')->plainTextToken;

\Illuminate\Support\Facades\Auth::forgetGuards();
\Illuminate\Support\Facades\Auth::logout();

$request3 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request3->headers->set('Authorization', "Bearer $tokenB");
$response3 = app()->handle($request3);

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count after B: " . count($aiRuns) . "\n";
foreach($aiRuns as $run) {
    echo $run->id . " - tenant: " . $run->tenant_id . "\n";
}
