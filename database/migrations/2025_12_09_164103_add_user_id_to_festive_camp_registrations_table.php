<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Just make sure the column exists, no foreign key
        if (!Schema::hasColumn('festive_camp_registrations', 'user_id')) {
            Schema::table('festive_camp_registrations', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')
                    ->nullable()
                    ->after('id');
            });
        }
    }

    public function down(): void
    {
        // Remove the column if it exists
        if (Schema::hasColumn('festive_camp_registrations', 'user_id')) {
            Schema::table('festive_camp_registrations', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
