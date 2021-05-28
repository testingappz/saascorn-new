<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFundamericaFieldsToUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            //
            $table->string('tax_id_number', 100);
            $table->string('executive_name', 100);
            $table->string('region_formed_in', 50);
            $table->date('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
             $table->dropColumn('tax_id_number');
             $table->dropColumn('executive_name');
             $table->dropColumn('region_formed_in');
             $table->dropColumn('date_of_birth');
        });
    }
}
