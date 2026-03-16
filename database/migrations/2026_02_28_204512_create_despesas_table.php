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
        Schema::create('despesas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->double('valor', 15, 2);
            $table->boolean('recorrente')->default(false);
            $table->boolean('ja_pago');
            $table->char('mes', 7)->index();
            $table->date('data_pagamento')->nullable();
            $table->foreignUuid('user_id')
              ->constrained()
              ->cascadeOnDelete();
            $table->foreignUuid('conta_id')
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
        Schema::dropIfExists('despesas');
    }
};
