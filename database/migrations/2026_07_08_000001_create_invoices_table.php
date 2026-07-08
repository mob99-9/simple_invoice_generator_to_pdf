<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');

            // "Company name and contact details" (the issuer, e.g. Infinity Limousine)
            $table->string('company_name');
            $table->text('company_address')->nullable();

            // "To" client
            $table->string('client_name');
            $table->text('client_address')->nullable();

            // Driver / payment info
            $table->string('driver_name')->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('due_date')->nullable();

            // Totals (kept on the invoice so they can be overridden/adjusted manually)
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_bank_charges', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
