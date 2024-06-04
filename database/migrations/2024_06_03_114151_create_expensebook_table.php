<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensebookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('expensebook')){
            Schema::create('expensebook', function (Blueprint $table) {
                $table->string('Invoice',20)->primary();
                $table->date('Date')->nullable();
                $table->string('ExpenseFromAccount',100)->nullable();
                $table->unsignedBigInteger('chart_of_account_id')->nullable();
                $table->float('Amount')->nullable();
                $table->string('Description',300)->nullable();
                $table->unsignedBigInteger('user')->nullable();

                $table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
                $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expensebook');
    }
}
