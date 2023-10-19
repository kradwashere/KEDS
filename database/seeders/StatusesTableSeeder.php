<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('statuses')->delete();
        
        \DB::table('statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Pending',
                'color' => 'bg-warning',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Ongoing',
                'color' => 'bg-success',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Completed',
                'color' => 'bg-dark',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Cancelled',
                'color' => 'bg-danger',
            ),
        ));
        
        
    }
}