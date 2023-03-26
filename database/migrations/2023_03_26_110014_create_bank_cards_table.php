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
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->id('bank_card_id');
            $table->string("account_number")->unique();
            $table->integer("cvv");
            $table->string("expiry_date");
            $table->integer("expiry_date_month");
            $table->integer("expiry_date_day");
            
            // foreign key on the table Users
            $table->foreignId('user_id')->references("user_id")->on("users");
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
        Schema::dropIfExists('bank_cards');
    }
};
