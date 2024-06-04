<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseBookDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('purchase_book_detail')){
            Schema::create('purchase_book_detail', function (Blueprint $table) {
                $table->string('Invoice',30)->nullable();
                $table->unsignedBigInteger('ItemName')->nullable();
                $table->Integer('Qty');
                $table->decimal('Price');
                $table->decimal('Total');
                $table->foreign('Invoice')->references('Invoice')->on('purchase_book')->onDelete('cascade');
                $table->foreign('ItemName')->references('id')->on('items')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_book_detail');
    }
}
