<?php

namespace Tests\Feature;

use App\Services\DatabaseSchemaRepairService;
use App\Services\HomepageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageUserSchemaRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_instructors_can_be_loaded_when_user_columns_are_missing(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('role');
            });
        }

        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('status');
            });
        }

        $repairService = app(DatabaseSchemaRepairService::class);
        $repairService->ensureUserProfileColumns();

        $instructors = app(HomepageService::class)->getInstructors();

        $this->assertInstanceOf(Collection::class, $instructors);
        $this->assertCount(0, $instructors);
    }
}
