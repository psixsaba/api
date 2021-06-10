<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceTransactionHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_transaction_historys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foreign_user_ID')->nullable();
            $table->foreign('foreign_user_ID')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('transfer_amount', 7, 2)->nullable();
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
        Schema::dropIfExists('balance_transaction_historys');
    }
}
