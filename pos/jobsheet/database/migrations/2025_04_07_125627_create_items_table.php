<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_type_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->timestamps();
            
            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};