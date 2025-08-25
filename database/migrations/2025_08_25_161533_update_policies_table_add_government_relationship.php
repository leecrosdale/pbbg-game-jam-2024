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
        Schema::table('policies', function (Blueprint $table) {
            // Add government relationship
            $table->foreignId('government_id')->nullable()->constrained()->onDelete('cascade');
            
            // Add new fields
            $table->integer('duration')->default(10)->after('cost');
            $table->boolean('active')->default(true)->after('duration');
            $table->json('effects')->nullable()->after('active');
            
            // Modify cost field to be decimal
            $table->decimal('cost', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropForeign(['government_id']);
            $table->dropColumn(['government_id', 'duration', 'active', 'effects']);
            $table->unsignedBigInteger('cost')->default(0)->change();
        });
    }
};
