<?php

use App\Enums\JobStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();

            $table->string('job_id')->unique();
            $table->string('type')->nullable();
            $table->integer('total')->default(0);
            $table->integer('processed')->default(0);
            $table->enum('status', JobStatus::values())->default(JobStatus::PENDING->value);
            $table->string('file_path')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_jobs');
    }
};
