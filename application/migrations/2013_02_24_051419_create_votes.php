<?php

class Create_Votes {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('votes', function($table){
			$table->increments('id');
			$table->integer('uid');
			$table->integer('pid');
			$table->integer('value');
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('votes');
	}

}