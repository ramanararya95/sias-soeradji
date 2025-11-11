<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('remember_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('selector');
            $table->string('token');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index('selector');
        });
    }

    public function down()
    {
        Schema::dropIfExists('remember_tokens');
    }
};