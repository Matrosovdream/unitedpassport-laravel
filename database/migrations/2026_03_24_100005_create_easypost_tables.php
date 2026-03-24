<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('easypost_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('easypost_id', 64)->unique();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->boolean('is_return')->default(false);
            $table->string('status', 50)->nullable()->index();
            $table->string('tracking_code', 100)->nullable()->index();
            $table->string('refund_status', 50)->nullable();
            $table->string('mode', 20)->default('test');
            $table->string('tracking_url', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('easypost_shipment_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('easypost_id', 64)->unique();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->string('address_type', 20)->index();
            $table->string('name', 255)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('street1', 255)->nullable();
            $table->string('street2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 20)->nullable()->index();
            $table->string('country', 2)->nullable()->index();
            $table->string('phone', 30)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('easypost_shipment_id', 64)->index();
            $table->timestamps();
        });

        Schema::create('easypost_shipment_labels', function (Blueprint $table) {
            $table->id();
            $table->string('easypost_id', 64)->unique();
            $table->string('easypost_shipment_id', 64)->index();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->integer('date_advance')->default(0);
            $table->string('integrated_form', 50)->nullable();
            $table->dateTime('label_date')->nullable()->index();
            $table->integer('label_resolution')->nullable();
            $table->string('label_size', 20)->nullable();
            $table->string('label_type', 50)->nullable();
            $table->string('label_file_type', 50)->nullable();
            $table->text('label_url')->nullable();
            $table->text('label_pdf_url')->nullable();
            $table->text('label_zpl_url')->nullable();
            $table->text('label_epl2_url')->nullable();
            $table->timestamps();
        });

        Schema::create('easypost_shipment_parcels', function (Blueprint $table) {
            $table->id();
            $table->string('easypost_id', 64)->unique();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('easypost_shipment_id', 64)->index();
            $table->timestamps();
        });

        Schema::create('easypost_shipment_rates', function (Blueprint $table) {
            $table->id();
            $table->string('easypost_id', 64)->unique();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->string('mode', 20)->default('test')->index();
            $table->string('service', 100)->nullable()->index();
            $table->string('carrier', 100)->nullable()->index();
            $table->decimal('rate', 12, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->decimal('retail_rate', 12, 2)->nullable();
            $table->char('retail_currency', 3)->nullable();
            $table->decimal('list_rate', 12, 2)->nullable();
            $table->char('list_currency', 3)->nullable();
            $table->string('billing_type', 50)->nullable();
            $table->integer('delivery_days')->nullable();
            $table->dateTime('delivery_date')->nullable()->index();
            $table->boolean('delivery_date_guaranteed')->nullable();
            $table->integer('est_delivery_days')->nullable();
            $table->string('easypost_shipment_id', 64)->index();
            $table->timestamps();
        });

        Schema::create('easypost_shipment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id')->nullable()->index();
            $table->string('easypost_shipment_id', 64)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('change_type', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('easypost_shipment_history');
        Schema::dropIfExists('easypost_shipment_rates');
        Schema::dropIfExists('easypost_shipment_parcels');
        Schema::dropIfExists('easypost_shipment_labels');
        Schema::dropIfExists('easypost_shipment_addresses');
        Schema::dropIfExists('easypost_shipments');
    }
};
