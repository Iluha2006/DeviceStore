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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->text('pros')->nullable(); // Достоинства товара
            $table->text('cons')->nullable(); // Недостатки товара
            $table->text('comment')->nullable(); // Комментарий
            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
