<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('purchasers')){
            Schema::create('purchasers', function (Blueprint $table) {
                $table->id();
                $table->string('PurchaserName',250)->nullable();
                $table->string('Contact',50)->nullable();
                $table->text('Address')->nullable();
                $table->unsignedBigInteger('user')->nullable();
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
        Schema::dropIfExists('purchasers');
    }
}
