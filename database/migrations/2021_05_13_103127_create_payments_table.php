<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id');
            $table->dateTime('start_at');
            $table->string('payment_ref');
            $table->dateTime('end_at');
            $table->dateTime('paid_at');
            $table->string('authorization')->nullable();
            $table->timestamps();

            $table->foreign('subscription_id', 'payments_subscription_id')->references('id')
                ->on('subscriptions')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
