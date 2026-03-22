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
            $table->char('mes_referencia', 7);
            $table->date('data_fechamento');
            $table->date('data_vencimento');
            $table->decimal('despesa_total', 15, 2)->default(0.00);
            $table->boolean('ja_foi_paga')->default(false);
            $table->foreignUuid('conta_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
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
