<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_book', function (Blueprint $table) {
            $table->date('Date');
            $table->string('Invoice',30)->primary();
            $table->string('PurchaserName',100)->nullable();
            $table->string('PurchaserAddress',100)->nullable();
            $table->decimal('Total');
            $table->decimal('Discount');
            $table->decimal('VATPercentage');
            $table->decimal('VATPercentageAmount');
            $table->decimal('FinalTotal');
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_book');
    }
}
