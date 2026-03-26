<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migration_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('table_key', 100)->index();
            $table->string('status', 20)->default('pending'); // pending, running, completed, failed
            $table->integer('current_page')->default(0);
            $table->integer('total_pages')->nullable();
            $table->integer('total_rows')->nullable();
            $table->integer('imported')->default(0);
            $table->integer('updated')->default(0);
            $table->integer('skipped')->default(0);
            $table->json('errors')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->string('source_password', 255)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migration_jobs');
    }
};
