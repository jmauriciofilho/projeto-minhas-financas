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
        Schema::create('receitas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->double('valor');
            $table->boolean('ja_recebido');
            $table->integer('mes');
            $table->date('data_recebimento');
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
        Schema::dropIfExists('receitas');
    }
};
