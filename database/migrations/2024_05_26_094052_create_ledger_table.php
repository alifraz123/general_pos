<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ledger')){
            Schema::create('ledger', function (Blueprint $table) {
                $table->id();
                $table->string('Invoice',20);
                $table->unsignedBigInteger('user');
                $table->integer('chart_of_accounts_id');
                $table->float('debit');
                $table->float('credit');
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
        Schema::dropIfExists('ledger');
    }
}
