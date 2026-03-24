<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
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

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')->nullable()->after('id')->index();
            $table->string('user_login', 60)->nullable()->after('name')->index();
            $table->string('display_name', 250)->nullable()->after('email');
            $table->string('user_url', 100)->nullable()->after('display_name');
            $table->integer('user_status')->default(0)->after('user_url');
            $table->foreignId('role_id')->nullable()->after('user_status')->constrained('user_roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['legacy_id', 'user_login', 'display_name', 'user_url', 'user_status', 'role_id']);
        });

        Schema::dropIfExists('user_role_rights');
        Schema::dropIfExists('user_roles');
    }
};
