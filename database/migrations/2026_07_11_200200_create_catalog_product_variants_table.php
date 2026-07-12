<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_product_variants', function (Blueprint $table): void {
            $table->id();
            $table->string('external_id');
            $table->foreignId('catalog_product_id')
                ->constrained('catalog_products')
                ->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestampTz('source_updated_at')->nullable()->index();
            $table->timestampTz('last_synced_at')->nullable()->index();
            $table->timestamps();

            $table->unique(
                ['catalog_product_id', 'external_id'],
                'catalog_variants_product_external_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_product_variants');
    }
};
