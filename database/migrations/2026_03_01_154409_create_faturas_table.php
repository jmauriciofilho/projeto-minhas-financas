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
        Schema::create('faturas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('mes_referencia');
            $table->integer('dia_fechamento');
            $table->integer('dia_vencimento');
            $table->boolean('ja_foi_paga')->default(false);
            $table->foreignUuid('cartao_id')
              ->constrained()
              ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
