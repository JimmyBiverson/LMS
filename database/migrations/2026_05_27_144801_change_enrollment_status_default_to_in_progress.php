<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('enrollments')->where('status', 'Active')->update(['status' => 'in_progress']);
    }

    public function down(): void
    {
        DB::table('enrollments')->where('status', 'in_progress')->update(['status' => 'Active']);
    }
};
