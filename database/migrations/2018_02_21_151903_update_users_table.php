<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('city')->after('email')->unsigned()->nullable();
            $table->string('social_security', 15)->after('city');
            $table->integer('position_id')->after('social_security')->unsigned()->nullable();
            $table->integer('position_experience')->after('position_id')->nullable();

            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('city')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city', 'social_security', 'position_id', 'position_experience']);
        });
    }
}
