<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_products', function (Blueprint $table): void {
            $table->id();
            $table->string('external_id')->unique();
            $table->foreignId('catalog_category_id')
                ->nullable()
                ->constrained('catalog_categories')
                ->nullOnDelete()
                ->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('image_url')->nullable();
            $table->string('brand')->nullable();
            $table->string('gtin', 32)->nullable()->index();
            $table->decimal('lowest_price', 10, 2)->nullable();
            $table->char('currency', 3)->default('EUR');
            $table->unsignedInteger('active_shop_count')->default(0);
            $table->boolean('is_available')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->timestampTz('source_updated_at')->nullable()->index();
            $table->timestampTz('last_synced_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_products');
    }
};
