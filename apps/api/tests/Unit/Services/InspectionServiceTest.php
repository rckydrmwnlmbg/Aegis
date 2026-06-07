<?php

namespace Tests\Unit\Services;

use App\Models\Inspection;
use App\Services\InspectionService;
use Exception;
use Mockery;
use Tests\TestCase;

class InspectionServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_start_inspection_throws_exception_when_not_draft()
    {
        $inspection = new Inspection();
        $inspection->status = 'in_progress';

        $service = new InspectionService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Inspection is not in draft state.');

        $service->startInspection($inspection, []);
    }
}
