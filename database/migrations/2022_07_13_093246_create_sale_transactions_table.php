<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_transactions', function (Blueprint $table) {
            $table->id('sale_transaction_id');
            $table->foreignId('payment_method_id')->nullable();
            $table->foreignId('sale_channel_id')->nullable();
            $table->date('date');
            $table->string('sale_amount')->nullable();
            $table->string('expense_amount')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('sale_transactions');
    }
}
