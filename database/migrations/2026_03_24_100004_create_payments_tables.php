<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->string('paysys', 100)->nullable();
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
    }
};
