<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('festive_camp_registrations', function (Blueprint $table) {
            // ✅ Photo for the player
            $table->string('player_photo_path')->nullable()->after('school');

            // ✅ Unique token used for QR/verification
            $table->string('verification_token', 64)->unique()->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('festive_camp_registrations', function (Blueprint $table) {
            $table->dropColumn(['player_photo_path', 'verification_token']);
        });
    }
};
