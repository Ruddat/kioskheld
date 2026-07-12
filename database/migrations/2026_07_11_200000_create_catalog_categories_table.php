<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->unsignedInteger('product_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestampTz('source_updated_at')->nullable()->index();
            $table->timestampTz('last_synced_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_categories');
    }
};
