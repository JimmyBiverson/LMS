<?php

namespace Tests\Feature;

use App\Services\DatabaseSchemaRepairService;
use App\Services\HomepageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BundleSchemaRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_recover_when_bundle_tables_are_missing(): void
    {
        Schema::dropIfExists('bundle_course');
        Schema::dropIfExists('bundles');

        $repairService = app(DatabaseSchemaRepairService::class);
        $repairService->ensureBundleTables();

        $bundles = app(HomepageService::class)->getBundles();

        $this->assertInstanceOf(Collection::class, $bundles);
        $this->assertCount(0, $bundles);
    }
}
