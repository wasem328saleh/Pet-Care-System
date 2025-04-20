<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIllnessesTable extends Migration {

	public function up()
	{
		Schema::create('illnesses', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name_illness');
			$table->text('symptoms');
			$table->text('treatment');
		});
	}

	public function down()
	{
		Schema::drop('illnesses');
	}
}
