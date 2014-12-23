<?php

use Illuminate\Database\Migrations\Migration;

class ConfideSetupUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Creates the users table
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('fbid')->nullable();
            $table->bigInteger('gid')->nullable();
            $table->bigInteger('twid')->nullable();
            $table->bigInteger('gitid')->nullable();
            $table->bigInteger('instaid')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('chat_status')->default(0);
            $table->enum('gender',array('M','F','N'));
            $table->date('dob')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('country')->default(1);
            $table->string('url')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('skype')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('bio')->nullable()->nullable();
            $table->date('last_login');
            $table->boolean('tour_taken')->default(0);
            $table->string('confirmation_code');
            $table->string('remember_token')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        // Creates password reminders table
        Schema::create('password_reminders', function ($table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('password_reminders');
        Schema::drop('users');
    }
}
