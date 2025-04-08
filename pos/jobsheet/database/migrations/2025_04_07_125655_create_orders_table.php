<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK ke users
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount', 5, 2)->default(0); // Diskon dalam persentase
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->dateTime('order_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}