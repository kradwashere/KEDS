<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DropdownsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dropdowns')->delete();
        
        \DB::table('dropdowns')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Normal',
                'type' => 'Room Type',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Vip',
                'type' => 'Room Type',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Single',
                'type' => 'Bed Type',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Double',
                'type' => 'Bed Type',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Triple',
                'type' => 'Bed Type',
            ),
        ));
        
        
    }
}