<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('transaction_status_number')->nullable();//order id in merchants portal
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            //
        });
    }
};
