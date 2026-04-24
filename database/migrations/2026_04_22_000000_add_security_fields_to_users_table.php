<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('failed_login_attempts')->default(0)->after('password');
            $table->timestamp('blocked_until')->nullable()->after('failed_login_attempts');
            $table->boolean('skip_email_verification')->default(false)->after('blocked_until');
        });

        DB::table('users')->update(['skip_email_verification' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['failed_login_attempts', 'blocked_until', 'skip_email_verification']);
        });
    }
};
