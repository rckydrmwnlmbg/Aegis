<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsaController extends Controller
{
    public function store(Request $request)
    {
        // Placeholder for JSA store logic
        return response()->json([
            'status' => 'success',
            'message' => 'JSA created successfully',
            'data' => []
        ], 201);
    }
}
