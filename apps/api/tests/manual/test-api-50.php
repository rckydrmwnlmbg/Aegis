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

$request1 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request1->headers->set('Authorization', "Bearer $token");
$response1 = app()->handle($request1);

\Illuminate\Support\Facades\Auth::forgetGuards();

$request2 = \Illuminate\Http\Request::create('/api/v1/sync/incidents', 'POST', $payload);
$request2->headers->set('Authorization', "Bearer $token");
$response2 = app()->handle($request2);

$aiRuns = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->get();
echo "Count without scopes: " . count($aiRuns) . "\n";

\Laravel\Sanctum\Sanctum::actingAs($user);
$existing = \App\Models\AiRun::where('workflow_entity_id', $uuid)->first();
echo "Found with actingAs: " . ($existing ? 'yes' : 'no') . "\n";
