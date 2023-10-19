<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customers')->delete();
        
        \DB::table('customers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Krad Jumli',
                'mobile' => '09123654789',
                'gender' => 'Male',
                'birthday' => '1999-03-11',
                'nationality' => 'Filipino',
                'identification' => 'Krad Jumli-1.png',
                'address' => 'test',
                'created_at' => '2023-09-02 13:54:07',
                'updated_at' => '2023-09-02 13:54:07',
            ),
        ));
        
        
    }
}