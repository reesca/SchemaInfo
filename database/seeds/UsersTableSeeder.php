<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // $password = bcrypt('q');
        $password = '$2y$10$v7UA4cNXzQYJFQgBSa2eo.2bl5xc29CcTv24ZiJg3Xa.1EfE.jU4W';
        DB::table('users')->insert([
            'email' => 'reesca@aol.com',
            'password' => $password,
            'user_id' => 'admin',
            'first_name' => 'Charles',
            'middle_name' => 'Andrew',
            'last_name' => 'Rees',
            // 'login_id' => 'reesca@aol.com',
            'role_id' => 1,
            // 'custname' => 'Fraud Investigators, Inc.',
            // 'disabled' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'email' => 'cr1',
            'password' => $password,
            'user_id' => 'CR1',
            'first_name' => 'Ima',
            'middle_name' => '',
            'last_name' => 'ClientRep',
            // 'login_id' => 'cr1',
            'role_id' => 2,
            // 'custname' => 'Fraud Investigators, Inc.',
            // 'disabled' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'email' => 'su',
            'password' => $password,
            'user_id' => 'SU',
            'first_name' => 'Sue',
            'middle_name' => '',
            'last_name' => 'Peruser',
            // 'login_id' => 'cr1',
            'role_id' => 3,
            // 'custname' => 'Fraud Investigators, Inc.',
            // 'disabled' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $users = factory(App\User::class, 3)->create();
    }
}
