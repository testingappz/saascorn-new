<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeInvestmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('make_investment', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('investor_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('investor_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('investments');
            $table->string('investment_id',100);
            $table->string('status',20);
            $table->string('response',255);
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
        Schema::dropIfExists('make_investment');
    }
}
