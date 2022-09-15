<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPaylablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_paylables', function (Blueprint $table) {
            $table->id('account_paylable_id');
            $table->string('debt');
            $table->string('pay');
            $table->string('customer_name');
            $table->string('customer_telp');
            $table->date('debt_date');
            $table->date('due_date');
            $table->string('note');
            $table->string('status');
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
        Schema::dropIfExists('account_paylables');
    }
}
