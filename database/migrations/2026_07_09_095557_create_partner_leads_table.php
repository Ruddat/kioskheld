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
        Schema::create('partner_leads', function (Blueprint $table) {
            $table->id();

            $table->string('business_name');
            $table->string('contact_name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();

            $table->string('street')->nullable();
            $table->string('postcode', 5);
            $table->string('city')->nullable();

            $table->text('opening_hours_note')->nullable();
            $table->string('delivery_possible')->default('maybe');
            $table->text('message')->nullable();

            $table->string('status')->default('new');
            $table->string('source')->default('kioskheld');
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('postcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_leads');
    }
};
