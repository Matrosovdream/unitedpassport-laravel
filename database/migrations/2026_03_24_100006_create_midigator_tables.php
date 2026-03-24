<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('midigator_preventions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('arn', 64)->nullable()->index();
            $table->string('card_brand', 30)->nullable();
            $table->string('card_first_6', 12)->nullable();
            $table->string('card_last_4', 8)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('merchant_descriptor', 255)->nullable();
            $table->string('mid', 64)->nullable()->index();
            $table->string('order_guid', 64)->nullable()->index();
            $table->string('order_id', 64)->nullable()->index();
            $table->string('prevention_case_number', 64)->nullable();
            $table->string('prevention_guid', 64)->unique();
            $table->dateTime('prevention_timestamp')->nullable()->index();
            $table->string('prevention_type', 50)->nullable()->index();
            $table->dateTime('transaction_timestamp')->nullable()->index();
            $table->boolean('is_resolved')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('midigator_rdr', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('arn', 64)->nullable()->index();
            $table->string('auth_code', 32)->nullable();
            $table->string('card_first_6', 12)->nullable();
            $table->string('card_last_4', 8)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('merchant_descriptor', 255)->nullable();
            $table->string('event_guid', 64)->nullable();
            $table->dateTime('event_timestamp')->nullable()->index();
            $table->string('event_type', 50)->nullable();
            $table->string('rdr_guid', 64)->unique();
            $table->string('rdr_case_number', 64)->nullable()->index();
            $table->date('rdr_date')->nullable()->index();
            $table->string('rdr_resolution', 80)->nullable()->index();
            $table->string('prevention_type', 50)->nullable()->index();
            $table->date('transaction_date')->nullable()->index();
            $table->string('order_id', 64)->nullable()->index();
            $table->boolean('is_resolved')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('midigator_resolves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prevention_id')->index();
            $table->string('prevention_guid', 64)->index();
            $table->string('resolution_type', 80)->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('prevention_id')->references('id')->on('midigator_preventions')->cascadeOnDelete();
        });

        Schema::create('midigator_resolve_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resolve_id')->index();
            $table->unsignedBigInteger('prevention_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('prevention_guid', 64)->index();
            $table->string('resolution_type', 80)->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('resolve_id')->references('id')->on('midigator_resolves')->cascadeOnDelete();
            $table->foreign('prevention_id')->references('id')->on('midigator_preventions')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['prevention_id', 'created_at']);
            $table->index(['resolve_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midigator_resolve_history');
        Schema::dropIfExists('midigator_resolves');
        Schema::dropIfExists('midigator_rdr');
        Schema::dropIfExists('midigator_preventions');
    }
};
