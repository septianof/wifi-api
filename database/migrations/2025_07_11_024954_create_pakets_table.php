<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->integer('harga');
            $table->string('kecepatan');
            $table->softDeletes(); 
            $table->timestamps();  
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};