<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_tutorial', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('kode_makul', 50);
            $table->string('url_presentation', 255)->unique();
            $table->string('url_finished', 255)->unique();
            $table->string('creator_email', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_tutorial');
    }
};
