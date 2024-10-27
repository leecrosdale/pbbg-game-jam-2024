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
        Schema::create('governments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('name');
            $table->unsignedBigInteger('available_population');
            $table->decimal('education')->default(0.00);
            $table->decimal('safety')->default(0);
            $table->decimal('health')->default(0);
            $table->decimal('economy')->default(0);
            $table->decimal('overall')->default(1);

            $table->unsignedBigInteger('education_population')->default(0);
            $table->unsignedBigInteger('safety_population')->default(0);
            $table->unsignedBigInteger('health_population')->default(0);
            $table->unsignedBigInteger('economy_population')->default(0);

            $table->unsignedBigInteger('money')->default(1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governments');
    }
};
