<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id');
            $table->foreignId('pod_id');            
            $table->string('status');
            $table->string('phone');
            $table->dateTime('booking_datetime');            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->change();
            $table->foreign('pod_id')->references('id')->on('pods')->onDelete('cascade')->change();        
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function($table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['pod_id']);
        });        
        Schema::dropIfExists('bookings');
    }
}
