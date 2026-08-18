<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('amount_paid')->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_provider')->nullable()->after('payment_method_id');
            $table->string('payment_reference')->nullable()->after('payment_provider');
            $table->string('payment_status')->nullable()->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropColumn(['payment_provider', 'payment_reference', 'payment_status']);
        });
    }
};
