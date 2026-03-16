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
        Schema::create('contas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->enum('tipo', ['CORRENTE', 'BENEFICIO']);
            $table->double('saldo', 15, 2)->default(0.00);
            $table->foreignUuid('user_id')
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
        Schema::dropIfExists('contas');
    }
};
