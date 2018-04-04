<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);

            $table->integer('position_id')->unsigned()->nullable();

            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });

        $this->initializeRoles();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position_roles');
    }

    protected function initializeRoles() {
        DB::table( 'position_roles' )->truncate();

        DB::table( 'position_roles' )->insert( [
            [
                'name'          => 'Backend',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Frontend',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Full Stack',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Data',
                'position_id'   =>  1
            ],
            [
                'name'          => 'DevOps',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Gaming',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Mobile',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Embedded Engineer',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Security',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Machine Learning',
                'position_id'   =>  1
            ],
            [
                'name'          => 'AR/VR',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Blockchain',
                'position_id'   =>  1
            ],
            [
                'name'          => 'NLP',
                'position_id'   =>  1
            ],
            [
                'name'          => 'QA Testing',
                'position_id'   =>  1
            ],
            [
                'name'          => 'Data Analyst',
                'position_id'   =>  2
            ],
            [
                'name'          => 'Data Scientist',
                'position_id'   =>  2
            ],
            [
                'name'          => 'Data Engineer',
                'position_id'   =>  2
            ],
            [
                'name'          => 'Project Management',
                'position_id'   =>  3
            ],
            [
                'name'          => 'Business Analyst',
                'position_id'   =>  3
            ],
            [
                'name'          => 'Product Management',
                'position_id'   =>  3
            ],
            [
                'name'          => 'IT Project Management',
                'position_id'   =>  3
            ],
            [
                'name'          => 'Program Management',
                'position_id'   =>  3
            ],
            [
                'name'          => 'Technical Program Manager',
                'position_id'   =>  3
            ],
            [
                'name'          => 'UX Designer',
                'position_id'   =>  4
            ],
            [
                'name'          => 'Graphic Designer',
                'position_id'   =>  4
            ],
            [
                'name'          => 'Product Designer',
                'position_id'   =>  4
            ],
            [
                'name'          => 'UI Developer',
                'position_id'   =>  4
            ],
            [
                'name'          => 'Network Engineer',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Systems Engineer',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Network Administrator',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Systems Administrator',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Desktop Support',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Security Engineer',
                'position_id'   =>  5
            ],
            [
                'name'          => 'Business Systems',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Solutions Architect',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Database Administrator',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Network Administrator',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Systems Administrator',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Desktop Support',
                'position_id'   =>  6
            ],
            [
                'name'          => 'NOC',
                'position_id'   =>  6
            ],
            [
                'name'          => 'Help Desk',
                'position_id'   =>  6
            ]
        ]);
    }
}
