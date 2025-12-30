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
        Schema::table('akuns', function(Blueprint $table){
            $table->foreignId('lawan_post')->nullable()->constrained('akuns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            $table->dropForeign(['lawan_post']);
            $table->dropColumn('lawan_post');
        });
    }
};
