<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('quality_status')->default('approved')->after('is_active');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('commission_amount', 12, 2)->default(0)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quality_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
        });
    }
};
