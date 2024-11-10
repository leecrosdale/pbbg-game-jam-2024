<?php

use App\Models\Government;
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
        Schema::create('government_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(\App\Models\Government::class)->constrained();
            $table->foreignIdFor(\App\Models\Infrastructure::class)->constrained();
            $table->unsignedBigInteger('level')->default(1);
            $table->unsignedBigInteger('population')->default(0);
            $table->decimal('efficiency')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_infrastructures');
    }
};
