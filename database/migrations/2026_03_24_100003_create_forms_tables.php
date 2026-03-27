<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('form_key', 100)->nullable()->unique();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_form_id')->default(0)->index();
            $table->boolean('logged_in')->nullable();
            $table->boolean('editable')->nullable();
            $table->boolean('is_template')->default(false);
            $table->boolean('default_template')->default(false);
            $table->string('status', 255)->nullable();
            $table->longText('options')->nullable();
            $table->timestamps();
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_key', 100)->nullable()->unique();
            $table->text('name')->nullable();
            $table->longText('description')->nullable();
            $table->text('type')->nullable();
            $table->longText('default_value')->nullable();
            $table->longText('options')->nullable();
            $table->integer('field_order')->default(0);
            $table->integer('page_num')->default(1);
            $table->integer('required')->nullable();
            $table->longText('field_options')->nullable();
            $table->unsignedBigInteger('form_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
        });

        Schema::create('form_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->string('code', 100)->index();
            $table->string('value', 255);
            $table->string('description', 255)->nullable();
            $table->string('color', 50)->nullable();
            $table->timestamps();

            $table->unique(['form_id', 'code']);
        });

        Schema::create('form_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_key', 100)->nullable()->unique();
            $table->string('name', 255)->nullable();
            $table->text('browser_info')->nullable();
            $table->text('ip')->nullable();
            $table->unsignedBigInteger('form_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreignId('status_id')->nullable()->constrained('form_statuses')->nullOnDelete();
            $table->boolean('is_draft')->default(false);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['is_draft', 'created_at']);
            $table->index(['form_id', 'is_draft']);
        });

        Schema::create('form_item_metas', function (Blueprint $table) {
            $table->id();
            $table->longText('meta_value')->nullable();
            $table->unsignedBigInteger('field_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('form_fields')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('form_items')->cascadeOnDelete();

            $table->index(['field_id', 'item_id']);
        });

        Schema::create('form_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->string('setting_code', 100)->index();
            $table->unsignedBigInteger('setting_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['form_id', 'setting_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_settings');
        Schema::dropIfExists('form_item_metas');
        Schema::dropIfExists('form_items');
        Schema::dropIfExists('form_statuses');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
    }
};
