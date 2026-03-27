<?php

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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_role_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_role_id')->constrained('user_roles')->cascadeOnDelete();
            $table->string('role_code', 100)->index();
            $table->unsignedBigInteger('role_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['user_role_id', 'role_code']);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->string('name');
            $table->string('user_login', 60)->nullable()->index();
            $table->string('email')->unique();
            $table->string('display_name', 250)->nullable();
            $table->string('user_url', 100)->nullable();
            $table->integer('user_status')->default(0);
            $table->foreignId('role_id')->nullable()->constrained('user_roles')->nullOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_role_rights');
        Schema::dropIfExists('user_roles');
    }
};
