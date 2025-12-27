<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Path to the image
            $table->string('title')->nullable(); // Main text
            $table->string('subtitle')->nullable(); // Small text
            $table->string('link')->nullable(); // Where it goes when clicked

            $table->enum('type', ['home_main', 'product_detail_banner', 'popup_ad'])->default('home_main');

            $table->integer('sort_order')->default(0); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};