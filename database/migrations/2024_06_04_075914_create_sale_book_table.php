<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sale_book')){
            Schema::create('sale_book', function (Blueprint $table) {
                $table->string('Ref',10);
                $table->date('Date');
                $table->datetime('invoiceDateTime');
                $table->string('Invoice',30)->primary();
                $table->string('OrderNo',100)->nullable();
                $table->unsignedBigInteger('CustomerName')->nullable();
                $table->string('Addres',250)->nullable();
                $table->string('Email',100)->nullable();
                $table->string('Cell',25)->nullable();
                $table->decimal('Total');
                $table->decimal('VATPercentage');
                $table->decimal('VATPercentageValue');
                $table->decimal('Discount');
                $table->decimal('FinalTotal');
                $table->unsignedBigInteger('user')->nullable();
                $table->string('Warrenty',100)->nullable();
    
                $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('CustomerName')->references('id')->on('customers')->onDelete('cascade');
    
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
        Schema::dropIfExists('sale_book');
    }
}
