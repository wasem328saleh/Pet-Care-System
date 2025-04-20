<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoctorsTable extends Migration {

	public function up()
	{
		Schema::create('doctors', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('full_Name_doctor');
			$table->string('phone_number');
			$table->text('address');
			$table->text('description');
		});
	}

	public function down()
	{
		Schema::drop('doctors');
	}
}
