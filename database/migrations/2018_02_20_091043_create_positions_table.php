<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 50);
        });

        $this->initializePositions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }

    protected function initializePositions() {
        DB::table( 'positions' )->truncate();

        DB::table( 'positions' )->insert( [
            [
                'name'     => 'Programming & Development',
            ],
            [
                'name'     => 'Data Science',
            ],
            [
                'name'     => 'Project Management',
            ],
            [
                'name'     => 'Design',
            ],
            [
                'name'     => 'Infrastructure / Systems Engineering',
            ],
            [
                'name'     => 'Information Technology (IT)',
            ],
            [
                'name'     => 'Executive / Management',
            ],
            [
                'name'     => 'Other',
            ]
        ]);
    }
}
