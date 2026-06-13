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

auth()->login($user);

$uuid = Str::uuid()->toString();

$run = new App\Models\AiRun([
    'id' => Str::uuid()->toString(),
    'actor_id' => $user->id,
    'use_case' => 'x',
    'workflow_domain' => 'y',
    'workflow_entity_id' => $uuid,
    'payload' => [],
    'status' => 'pending',
    'occurred_at' => now(),
]);
$run->tenant_id = $tenant->id;
$run->save();

$existing = \App\Models\AiRun::where('workflow_entity_id', $uuid)->first();
echo "Found: " . ($existing ? 'yes' : 'no') . "\n";
if ($existing) {
    echo "ID: " . $existing->id . "\n";
} else {
    $existing = \App\Models\AiRun::withoutGlobalScopes()->where('workflow_entity_id', $uuid)->first();
    echo "Without scopes found: " . ($existing ? 'yes' : 'no') . "\n";
}
