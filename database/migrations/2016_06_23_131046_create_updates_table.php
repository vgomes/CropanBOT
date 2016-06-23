<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('update_id')->unique();
            $table->bigInteger('user_id');
            $table->string('type');
            $table->bigInteger('reply_to')->nullable();
            $table->text('text')->nullable();
            $table->longText('content');
            $table->date('date');

            $table->softDeletes();
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
        Schema::drop('updates');
    }
}
