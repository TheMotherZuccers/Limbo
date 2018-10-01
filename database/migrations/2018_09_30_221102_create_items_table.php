<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->comment('describe what the item looks like/what it is');
            $table->string('notable_damage')->nullable()->comment('note any damage to the item when it was received by the lost and found office');
            $table->enum('environment_found', ['inside', 'sunny', 'rain', 'snow', 'humid'])->comment('state the environment the item was found in');
            $table->point('position_found')->comment('Coordinates the item was found at');
            $table->integer('position_radius')->nullable()->comment('Radius around position_found the item may have been in');
            $table->string('position_comment')->nullable()->comment('added details on where the item was found');
            $table->unsignedInteger('finder_id');
            $table->foreign('finder_id')->references('id')->on('users');
            $table->unsignedInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('items');
    }
}
