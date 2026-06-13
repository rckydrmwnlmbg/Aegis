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

echo "Creating AiRun with new directly:\n";
$aiRun = new App\Models\AiRun([
    'id' => Str::uuid()->toString(), 'actor_id' => $user->id, 'use_case' => 'incident_sync',
    'workflow_domain' => 'incident', 'workflow_entity_id' => Str::uuid()->toString(), 'payload' => [], 'status' => 'pending', 'occurred_at' => now(),
]);
$aiRun->tenant_id = $tenant->id;
$aiRun->save();
echo "Done.\n";
