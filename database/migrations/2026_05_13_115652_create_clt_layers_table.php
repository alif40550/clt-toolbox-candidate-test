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
        Schema::create('clt_layers', function (Blueprint $table) {
            $table->id();
            $table->integer('layer_order');
            $table->decimal('thickness');
            $table->decimal('width');
            $table->decimal('angle');
            $table->dateTime('last_modified');
            $table->timestamps();

            $table->foreignId('layup_id')->constrained('clt_layups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clt_layers');
    }
};
