<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('gateway', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_method_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->string('label', 255)->nullable();
            $table->string('login_id', 255)->nullable();
            $table->string('transaction_key', 255)->nullable();
            $table->string('api_key', 255)->nullable();
            $table->string('api_secret', 255)->nullable();
            $table->string('public_key', 255)->nullable();
            $table->string('merchant_id', 255)->nullable();
            $table->string('environment', 20)->default('sandbox');
            $table->text('webhook_secret')->nullable();
            $table->text('extra')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('form_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_id', 100)->nullable()->index();
            $table->string('invoice_id', 100)->nullable()->index();
            $table->string('sub_id', 100)->nullable();
            $table->unsignedBigInteger('item_id')->index();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status', 100)->nullable()->index();
            $table->date('begin_date');
            $table->date('expire_date')->nullable();
            $table->foreignId('payment_method')->default(1)->constrained('payment_methods');
            $table->boolean('test')->nullable();
            $table->timestamps();
        });

        Schema::create('payments_authnet', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('payment_id')->index();
            $table->string('invoice_id', 255)->nullable()->index();
            $table->unsignedBigInteger('entry_id')->index();
            $table->unsignedBigInteger('form_id')->index();
            $table->string('authnet_login_id', 100);
            $table->string('authnet_transaction_key', 100);
            $table->timestamps();

            $table->index(['form_id', 'created_at']);
        });

        Schema::create('payments_failed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id')->index();
            $table->unsignedBigInteger('form_id')->index();
            $table->unsignedBigInteger('payment_id')->index();
            $table->string('error_code', 100);
            $table->text('error_message');
            $table->timestamps();

            $table->index(['form_id', 'created_at']);
        });

        Schema::create('refunds_authnet', function (Blueprint $table) {
            $table->id();
            $table->decimal('sum', 12, 2);
            $table->unsignedBigInteger('payment_id')->index();
            $table->timestamps();

            $table->index(['payment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds_authnet');
        Schema::dropIfExists('payments_failed');
        Schema::dropIfExists('payments_authnet');
        Schema::dropIfExists('form_payments');
        Schema::dropIfExists('payment_method_accounts');
        Schema::dropIfExists('payment_methods');
    }
};
