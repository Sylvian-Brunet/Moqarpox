<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message');
            $table->string('title');
            $table->integer('note');
            $table->smallInteger('state')->default(0); // 0 en attente de validation | 1 validé | -1 refusé

            $table->unsignedBigInteger('activity_order_id');
            $table->unsignedBigInteger('activity_id');

            $table->foreign('activity_order_id')->references('id')->on('activities_orders');
            $table->foreign('activity_id')->references('id')->on('activities');

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
        Schema::dropIfExists('comments');
    }
}
