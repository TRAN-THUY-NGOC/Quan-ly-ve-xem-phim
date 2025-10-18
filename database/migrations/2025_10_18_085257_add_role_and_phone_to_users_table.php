<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nếu bạn chưa có cột phone thì thêm
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }

            // Thêm cột role, mặc định là 'user'
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone']);
        });
    }
};
