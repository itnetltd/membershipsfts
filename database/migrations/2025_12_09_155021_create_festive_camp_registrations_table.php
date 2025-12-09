<?php

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
    Schema::create('festive_camp_registrations', function (Blueprint $table) {
        $table->id();
        $table->string('player_name');
        $table->unsignedTinyInteger('age')->nullable();
        $table->string('category')->nullable(); // U8, U10, U12, etc.
        $table->string('school')->nullable();

        $table->string('guardian_name');
        $table->string('guardian_phone');
        $table->string('guardian_email')->nullable();

        $table->string('payment_method')->default('MoMo');
        $table->string('payment_phone')->nullable();
        $table->string('payment_reference')->nullable();

        $table->string('status')->default('pending'); // pending, approved, cancelled

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('festive_camp_registrations');
}

};
