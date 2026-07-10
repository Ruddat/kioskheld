<?php

use App\Models\PartnerLead;
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
        Schema::create('partner_onboardings', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(PartnerLead::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('token', 96)->unique();

            $table->string('status')->default('draft');
            // draft, sent, submitted, expired

            $table->json('business_data')->nullable();
            $table->json('selected_categories')->nullable();
            $table->json('opening_hours')->nullable();
            $table->json('delivery_settings')->nullable();
            $table->json('payment_settings')->nullable();

            $table->timestamp('accepted_terms_at')->nullable();
            $table->string('accepted_terms_ip')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_onboardings');
    }
};
