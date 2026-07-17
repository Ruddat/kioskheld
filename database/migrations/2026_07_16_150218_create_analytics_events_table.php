<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();

            $table->string('event_name', 100)->index();

            /*
             * Datenschutzfreundlicher Besucher-Identifier.
             * Keine Klartext-IP.
             */
            $table->string('visitor_hash', 64)->nullable()->index();
            $table->string('session_id', 120)->nullable()->index();

            $table->string('locale', 10)->nullable()->index();
            $table->string('postcode', 10)->nullable()->index();

            /*
             * IDs kommen teilweise aus JustDeliver und besitzen deshalb
             * bewusst keine lokalen Foreign-Key-Constraints.
             */
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->string('external_order_id', 120)->nullable()->index();

            $table->string('route_name', 200)->nullable();
            $table->text('url')->nullable();
            $table->text('referer')->nullable();

            /*
             * Zusätzliche Informationen, beispielsweise:
             *
             * {
             *   "available": true,
             *   "shop_count": 2,
             *   "response_time_ms": 341
             * }
             */
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['event_name', 'created_at']);
            $table->index(['postcode', 'event_name', 'created_at']);
            $table->index(['shop_id', 'event_name', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
