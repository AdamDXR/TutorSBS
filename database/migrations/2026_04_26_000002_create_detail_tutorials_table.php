<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tutorial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_tutorial_id')
                  ->constrained('master_tutorial')
                  ->onDelete('cascade');
            $table->text('text')->nullable();
            $table->text('gambar')->nullable();
            $table->text('code')->nullable();
            $table->string('url', 500)->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['show', 'hide'])->default('show');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tutorial');
    }
};
