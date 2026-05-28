<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ApiResponseTest extends TestCase
{
    public function test_api_middleware_adds_correlation_id()
    {
        Route::get('/api/test-correlation', function () {
            return response()->json(['data' => []]);
        })->middleware('api');

        $response = $this->get('/api/test-correlation');

        $response->assertStatus(200);
        $response->assertHeader('X-Correlation-ID');
        $this->assertNotEmpty($response->headers->get('X-Correlation-ID'));
    }

    public function test_api_exception_format()
    {
        Route::get('/api/test-exception', function () {
            abort(404, 'Resource not found');
        })->middleware('api');

        $response = $this->getJson('/api/test-exception');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error' => [
                'code',
                'message',
                'details'
            ]
        ]);

        $response->assertJson([
            'error' => [
                'code' => 'HTTP_ERROR_404',
                'message' => 'Resource not found',
                'details' => []
            ]
        ]);
    }
}