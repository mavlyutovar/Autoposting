<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPostTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_times', function (Blueprint $table) {
            $table->integer('userid');
            $table->json('weak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_times', function (Blueprint $table) {
            $table->dropColumn('userid');
            $table->dropColumn('weak');
        });
    }
}
