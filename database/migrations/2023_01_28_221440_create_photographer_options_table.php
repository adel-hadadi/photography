<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photographer_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')->constrained('users')->cascadeOnUpdate();
            $table->string('name');
            $table->enum('price_type', ['allPictures', 'eachPicture'])->default('eachPicture');
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photographer_options');
    }
};
