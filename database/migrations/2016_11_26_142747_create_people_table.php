<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        \Schema::create('people_pictures', function (Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('picture_id');

            $table->primary(['person_id', 'picture_id']);

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('picture_id')->references('id')->on('pictures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('people_pictures');
        \Schema::dropIfExists('people');
    }
}
