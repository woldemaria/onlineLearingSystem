<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 30)->default('student')->after('email');
            });
        }

        // Backfill roles for baseline seed data
        DB::table('users')->where('id', 1)->update(['role' => 'admin']);
        DB::table('users')->where('id', 4)->update(['role' => 'student']);
        DB::table('users')->whereNotIn('id', [1, 4])->update(['role' => 'instructor']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
