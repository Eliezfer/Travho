<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('country');
          	$table->string('state');
            $table->string('municipality');
          	$table->string('street');
            $table->string('crossStreet1');
          	$table->string('crossStreet2');
            $table->string('numberHouse');
            $table->string('suburb');
            $table->string('postcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}