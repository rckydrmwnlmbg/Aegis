<?php

namespace App\Http\Controllers;

use App\Models\InspectionTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InspectionTemplateController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', InspectionTemplate::class);

        // Global TenantScope handles tenant isolation
        $templates = InspectionTemplate::with('items')->get();

        return response()->json([
            'data' => $templates,
            'meta' => [
                'count' => $templates->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', InspectionTemplate::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.question_text' => 'required|string',
            'items.*.response_type' => 'required|in:yes_no,text,numeric,photo',
            'items.*.is_required' => 'boolean',
            'items.*.order_index' => 'required|integer',
        ]);

        $template = DB::transaction(function () use ($validated, $request) {
            $template = InspectionTemplate::create([
                'tenant_id' => $request->user()->tenant_id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            foreach ($validated['items'] as $item) {
                $template->items()->create([
                    'tenant_id' => $request->user()->tenant_id,
                    'question_text' => $item['question_text'],
                    'response_type' => $item['response_type'],
                    'is_required' => $item['is_required'] ?? true,
                    'order_index' => $item['order_index'],
                ]);
            }

            return $template->load('items');
        });

        return response()->json([
            'data' => $template,
            'meta' => [
                'message' => 'Inspection template created successfully.',
            ],
        ], 201);
    }

    public function show(Request $request, InspectionTemplate $inspectionTemplate)
    {
        Gate::authorize('view', $inspectionTemplate);

        return response()->json([
            'data' => $inspectionTemplate->load('items'),
            'meta' => [],
        ]);
    }
}
