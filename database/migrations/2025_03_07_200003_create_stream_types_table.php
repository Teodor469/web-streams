<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamTypesTable extends Migration
{

    public function up()
    {
        Schema::create('stream_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stream_types');
    }
}
