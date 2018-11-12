<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BetterItemInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function($table) {
            $table->text('contact')->nullable();
            $table->boolean('hidden')->default(false);
            $table->boolean('lost');
            $table->boolean('claimed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function($table) {
            $table->dropColumn('contact');
            $table->dropColumn('hidden');
            $table->dropColumn('lost');
            $table->dropColumn('claimed');
        });
    }
}
