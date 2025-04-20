<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClinicsTable extends Migration {

	public function up()
	{
		Schema::create('clinics', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('full_Name_clinic');
			$table->string('phone_number');
			$table->text('address');
			$table->text('available_doctors');
			$table->text('working_days');
			$table->text('description');
		});
	}

	public function down()
	{
		Schema::drop('clinics');
	}
}
