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
        Schema::create('classificacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->foreignUuid('classificacao_id')->nullable()->constrained('classificacoes')->onDelete('set null');
        });

        Schema::table('despesas', function (Blueprint $table) {
            $table->foreignUuid('classificacao_id')->nullable()->constrained('classificacoes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['classificacao_id']);
            $table->dropColumn('classificacao_id');
        });
        
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropForeign(['classificacao_id']);
            $table->dropColumn('classificacao_id');
        });

        Schema::dropIfExists('classificacoes');
    }
};
