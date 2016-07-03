<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('password');

            $table->renameColumn('name', 'nickname');
            $table->string('avatar')->nullable();
        });

        $users = getAllowedUsers();

        if (count($users) > 0) {
            foreach ($users as $user) {
                \Cropan\User::create([
                    'telegram_id' => $user['telegram_id'],
                    'nickname' => $user['nickname']
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
