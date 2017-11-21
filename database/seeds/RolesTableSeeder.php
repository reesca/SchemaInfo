<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $roles = [
        	'Administrator',
            'Client Representative',
        	'Super User',
        	'Fraud Case Manager',
        	'Claim Specialist',
        	'Third Party',
        ];

        foreach ($roles as $role) {
	        
	        DB::table('roles')->insert([
	            'name' => $role,
	            'created_at' => $now,
	            'updated_at' => $now,
	        ]);

	    }
    }
}
