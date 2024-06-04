<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ShowEmailOnInvoice',20)->nullable();
            $table->string('email')->unique()->nullable;
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('CRNO',30)->nullable();
            $table->string('UserType',6)->nullable();
            $table->string('CompanyName',60)->nullable();
            $table->string('CompanyNameArabic',60)->nullable();
            $table->string('VATNO',30)->nullable();
            $table->string('image',200)->nullable();
            $table->string('cell',25)->nullable();
            $table->string('Addres',100)->nullable();
            $table->string('ArticleNo',3)->unique()->nullable();
            $table->string('user_template',50)->nullable();
            $table->text('Detail_English')->nullable();
            $table->text('Detail_Arabic')->nullable();
            $table->text('Side_Detail_English')->nullable();
            $table->text('Side_Detail_Arabic')->nullable();
            $table->string('Invoice_pic',100)->nullable();
            $table->string('BusinessTypeEnglish',200)->nullable();
            $table->string('BusinessTypeArabic',200)->nullable();
            $table->string('BusinessDescriptionEnglish',200)->nullable();
            $table->string('BusinessDescriptionArabic',200)->nullable();
            $table->string('VATNO_Arabic',100)->nullable();
            $table->string('domainName',100)->nullable();
            $table->string('AccountNo',200)->nullable();
            $table->string('IBAN',200)->nullable();
            $table->string('VATPercentage',200)->nullable();
            $table->string('language',100)->nullable();
            $table->string('CustomerIndustry',200)->nullable();
            $table->string('SaleType',200)->nullable();
            $table->string('VAT_Calculation',100)->nullable();
            $table->string('SubscriptionMessage',100)->nullable();
            $table->string('UserStatus',100)->nullable();
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
        Schema::dropIfExists('users');
    }
}
