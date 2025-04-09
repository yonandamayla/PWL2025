<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoAndDescriptionToItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('photo')->nullable()->after('stock');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['description', 'photo']);
        });
    }
}