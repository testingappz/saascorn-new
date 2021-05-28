<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('accredited_investor_type')->comment = '1=>yes,2=>no,3=>i dont know';
            $table->tinyInteger('accredited_type')->comment = '1=>bank,2=>business,3=>corporation,4=>employee,5=>individual,6=>with spouse,7=>trust';
            $table->string('company_name',50);
            $table->integer('phone');
            $table->string('address_line_1',255);
            $table->string('address_line_2',255);
            $table->string('city',50);
            $table->string('state',50);
            $table->string('country',50);
            $table->string('zipcode',50);
            $table->tinyInteger('investor_type')->comment = '1=>person,2=>entity';
            $table->decimal('max_investment', 10, 2);
            $table->decimal('annual_income', 10, 2);
            $table->decimal('networth', 10, 2);
            $table->decimal('last_investment', 10, 2);
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
        Schema::dropIfExists('user_details');
    }
}
