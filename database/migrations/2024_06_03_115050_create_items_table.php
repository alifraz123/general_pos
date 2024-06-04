<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('items')){
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('ItemName', 200)->nullable();
                $table->float('Rate')->nullable();
                $table->float('PurchaseRate')->nullable();
                $table->unsignedBigInteger('user')->nullable();
                $table->integer('Qty')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('ItemCode', 300)->nullable();
                $table->string('BarCode', 200)->nullable();

                $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
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
        Schema::dropIfExists('items');
    }
}
