<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'super_reason')) {
                $table->text('super_reason')->nullable()->after('is_super_instructor')->comment('Admin reason for super instructor promotion');
            }
            if (!Schema::hasColumn('users', 'super_reason_date')) {
                $table->timestamp('super_reason_date')->nullable()->after('super_reason')->comment('Date when super status was set');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'super_reason')) {
                $table->dropColumn('super_reason');
            }
            if (Schema::hasColumn('users', 'super_reason_date')) {
                $table->dropColumn('super_reason_date');
            }
        });
    }
};
