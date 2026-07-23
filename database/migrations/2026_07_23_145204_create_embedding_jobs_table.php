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
        Schema::create('embedding_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->unique();
            $table->enum('status', JobStatus::values())->default(JobStatus::PENDING->value);
            $table->integer('total')->default(0);
            $table->integer('processed')->default(0);
            $table->longText('log')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embedding_jobs');
    }
};
