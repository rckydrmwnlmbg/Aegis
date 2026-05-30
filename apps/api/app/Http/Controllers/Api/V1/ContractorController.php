<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0
            ]
        ], 200);
    }
}
