// database/migrations/2023_06_20_000001_create_watermark_logs_images_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('watermark_logs_images', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('watermarked_filename');
            $table->integer('file_size');
            $table->string('file_type')->default('image');
            $table->string('file_path');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('watermark_logs_images');
    }
};