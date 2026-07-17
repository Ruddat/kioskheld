<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();

            /*
             * Datenschutzfreundlicher Besucher-Identifier.
             * Wir speichern keine Klartext-IP.
             */
            $table->string('visitor_hash', 64)->index();

            $table->string('session_id', 120)->nullable()->index();
            $table->string('user_agent', 500)->nullable();

            $table->string('method', 10);
            $table->string('route_name', 200)->nullable()->index();
            $table->text('url')->nullable();
            $table->text('referer')->nullable();

            $table->unsignedSmallInteger('response_status')->default(200);
            $table->unsignedInteger('response_time_ms')->default(0);

            $table->boolean('is_bot')->default(false)->index();
            $table->string('bot_name', 100)->nullable();

            $table->string('locale', 10)->nullable()->index();
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();

            $table->timestamps();

            $table->index(['created_at', 'is_bot']);
            $table->index(['route_name', 'created_at']);
            $table->index(['shop_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
