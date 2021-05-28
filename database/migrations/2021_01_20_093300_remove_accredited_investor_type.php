<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAccreditedInvestorType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
      {
          Schema::table('users', function($table) {
             $table->dropColumn('accredited_investor_type');
          });
      }

      public function down()
      {
          Schema::table('users', function($table) {
             $table->integer('accredited_investor_type');
          });
      }
}
