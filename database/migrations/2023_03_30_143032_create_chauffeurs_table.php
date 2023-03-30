<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id('driver_id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('matricule')->unique();
            $table->string('password');
            $table->string("pays");
            $table->string("ville");

            // foreign key - chauffeur
            $table->foreignId('principal_driver_id')->references("driver_id")->on("chauffeurs");

            // foreign key - car
            $table->foreignId('car_id')->references("car_id")->on("voitures");
            
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
        Schema::dropIfExists('chauffeurs');
    }
};
