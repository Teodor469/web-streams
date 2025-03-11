<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{

    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->string('description', 655)->nullable();
            $table->integer('tokens_price');
            $table->dateTime('date_expiration');
            $table->foreignId('type_id')->nullable()->constrained('stream_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('streams');
    }
}
