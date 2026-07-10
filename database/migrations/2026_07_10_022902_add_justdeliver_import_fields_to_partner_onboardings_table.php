<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_onboardings', function (Blueprint $table) {
            $table->string('justdeliver_import_status', 40)
                ->default('pending')
                ->after('status');

            $table->unsignedBigInteger('justdeliver_shop_id')
                ->nullable()
                ->after('justdeliver_import_status');

            $table->string('justdeliver_shop_slug', 190)
                ->nullable()
                ->after('justdeliver_shop_id');

            $table->text('justdeliver_import_error')
                ->nullable()
                ->after('justdeliver_shop_slug');

            $table->json('justdeliver_import_response')
                ->nullable()
                ->after('justdeliver_import_error');

            $table->timestamp('justdeliver_imported_at')
                ->nullable()
                ->after('justdeliver_import_response');
        });
    }

    public function down(): void
    {
        Schema::table('partner_onboardings', function (Blueprint $table) {
            $table->dropColumn([
                'justdeliver_import_status',
                'justdeliver_shop_id',
                'justdeliver_shop_slug',
                'justdeliver_import_error',
                'justdeliver_import_response',
                'justdeliver_imported_at',
            ]);
        });
    }
};
