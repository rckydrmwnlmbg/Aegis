<?php
namespace Tests\Feature\Api\V1;
use App\Models\AppUser;
use App\Models\Attachment;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Str;

class AttachmentTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void {
        parent::setUp();
        $this->tenant = Tenant::create(['id' => Str::uuid()->toString(), 'tenant_code' => 'TEST01', 'name' => 'Test Tenant']);
        $this->user = AppUser::factory()->create(['id' => Str::uuid()->toString(), 'tenant_id' => $this->tenant->id, 'email' => 'test@example.com', 'password' => bcrypt('password')]);
        Sanctum::actingAs($this->user);
    }
    public function test_can_upload_attachment() {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('evidence.jpg');
        $response = $this->postJson('/api/v1/attachments', ['file' => $file]);
        $response->assertStatus(201)->assertJsonStructure(['status', 'data' => ['id']]);
        $attachmentId = $response->json('data.id');
        $this->assertDatabaseHas('attachments', ['id' => $attachmentId, 'tenant_id' => $this->tenant->id, 'created_by' => $this->user->id, 'status' => 'uploaded']);
        $attachment = Attachment::find($attachmentId);
        Storage::disk('local')->assertExists($attachment->storage_key);
    }
    public function test_fails_if_file_not_provided() {
        $response = $this->postJson('/api/v1/attachments', [], ['Accept' => 'application/json']);
        $response->assertStatus(422);
    }
}
