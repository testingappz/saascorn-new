<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->foreign('investment_id')->references('id')->on('investments');
            $table->tinyInteger('type')->comment = '1=>doc,2=>video';
            $table->string('doc_name',100);
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
        Schema::dropIfExists('investment_docs');
    }
}
