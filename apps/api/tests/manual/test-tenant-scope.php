<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\AppUser;
use App\Models\AiRun;
use Illuminate\Support\Str;

$tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => Str::random(10), 'name' => 'T']);
$user = AppUser::factory()->create(['id' => Str::uuid()->toString(), 'tenant_id' => $tenant->id, 'email' => Str::random(10).'@e.com', 'password' => 'x']);

auth()->login($user);
echo "Auth check: " . (auth()->check() ? 'yes' : 'no') . "\n";
echo "Tenant ID: " . auth()->user()->tenant_id . "\n";

$aiRun = new AiRun();
echo "Table: " . $aiRun->getTable() . "\n";
$query = $aiRun->newQuery();
echo "SQL: " . $query->toSql() . "\n";
