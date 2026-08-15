<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Mở rộng cột name và slug trong bảng majors thành TEXT để chứa các tên siêu dài
        Schema::table('majors', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('slug')->change();
        });

        // 2. Thêm cột finished_at cho bảng import_jobs nếu chưa có
        Schema::table('import_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('import_jobs', 'finished_at')) {
                $table->timestamp('finished_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('slug', 255)->change();
        });

        Schema::table('import_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('import_jobs', 'finished_at')) {
                $table->dropColumn('finished_at');
            }
        });
    }
};
