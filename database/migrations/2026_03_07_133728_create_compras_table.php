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
        Schema::create('compras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('descricao');
            $table->date('data_compra');
            $table->decimal('valor', 10, 2);
            $table->integer('total_parcelas');
            $table->integer('numero_parcela');
            $table->foreignUuid('fatura_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
