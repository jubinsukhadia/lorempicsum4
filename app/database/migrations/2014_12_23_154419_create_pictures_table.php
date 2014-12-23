<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pictures', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name',60)->nullable();
			$table->string('slug',60)->nullable();
			$table->text('description')->nullable();
			$table->integer('views')->default(0);
			$table->integer('downloads')->default(0);
			$table->integer('likes')->default(0);
			$table->boolean('approved')->default(1);
			$table->boolean('allow_download')->default(1);
			$table->string('url')->default(0);
			$table->string('large_url')->nullable();
			$table->integer('source_id')->nullable();
			$table->string('copyright', 20)->nullable();
			$table->string('site', 20)->nullable();
			$table->integer('width')->nullable();
			$table->integer('height')->nullable();
			$table->boolean('downloaded_locally')->default(0);
			$table->enum('type',array('photo','vector','illustration','icon','video'))->default('photo');
			$table->enum('dimension',array('horizontal','vertical','square','na'))->default('na');
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
		Schema::drop('pictures');
	}

}
