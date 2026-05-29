<?php
namespace App\Http\Controllers\Api\V1;
use App\Actions\Attachment\UploadAttachmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachmentRequest;
use Illuminate\Http\JsonResponse;
class AttachmentController extends Controller {
    public function store(StoreAttachmentRequest $request, UploadAttachmentAction $action): JsonResponse {
        $attachment = $action->execute($request->file('file'), $request->user()->tenant_id, $request->user()->id);
        return response()->json(['status' => 'success', 'data' => ['id' => $attachment->id]], 201);
    }
}
