<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('employment_type', ['Permanent', 'Contract', 'Intern'])->default('permanent');
            $table->boolean('work_authorization')->default(true);
            $table->integer('usdod');
            $table->boolean('require_sponsorship')->default(true);
            $table->text('additional_info');
            $table->decimal('current_base_salary', 10, 2)->nullable();
            $table->decimal('current_contract_rate', 10, 2)->nullable();
            $table->decimal('target_base_salary', 10, 2)->nullable();
            $table->decimal('target_contract_rate', 10, 2)->nullable();
            $table->integer('searching_status');
            $table->timestamp('date_available');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('user_preferences');
    }
}
