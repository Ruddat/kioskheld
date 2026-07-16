<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_onboardings', function (Blueprint $table) {
            $table->string('justdeliver_remote_status', 50)
                ->nullable()
                ->after('justdeliver_import_status');

            $table->boolean('justdeliver_can_import_products')
                ->default(false)
                ->after('justdeliver_remote_status');

            $table->boolean('justdeliver_can_accept_orders')
                ->default(false)
                ->after('justdeliver_can_import_products');

            $table->json('justdeliver_status_response')
                ->nullable()
                ->after('justdeliver_can_accept_orders');

            $table->text('justdeliver_status_error')
                ->nullable()
                ->after('justdeliver_status_response');

            $table->timestamp('justdeliver_status_checked_at')
                ->nullable()
                ->after('justdeliver_status_error');

            $table->timestamp('justdeliver_activated_at')
                ->nullable()
                ->after('justdeliver_status_checked_at');
        });
    }

    public function down(): void
    {
        Schema::table('partner_onboardings', function (Blueprint $table) {
            $table->dropColumn([
                'justdeliver_remote_status',
                'justdeliver_can_import_products',
                'justdeliver_can_accept_orders',
                'justdeliver_status_response',
                'justdeliver_status_error',
                'justdeliver_status_checked_at',
                'justdeliver_activated_at',
            ]);
        });
    }
};
