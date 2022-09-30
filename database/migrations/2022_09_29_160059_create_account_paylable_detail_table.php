<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPaylableDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_paylable_detail', function (Blueprint $table) {
            $table->id('account_paylable_detail_id');
            $table->foreignId('account_paylable_id')->nullable();
            $table->string('pay_amount')->nullable();
            $table->date('pay_date')->nullable();
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
        Schema::dropIfExists('account_paylable_detail');
    }
}
