<?php

use Illuminate\Database\Seeder;

require_once(base_path() . '/database/functions.php');

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTypesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(UsersToClientsTableSeeder::class);
        $this->call(ClaimantsTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(GroupMembersTableSeeder::class);
        $this->call(CasesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}


// these functions are available to all the seeders and factories
// function autoIncrement()
// {
//     for ($i = 0; $i < 1000; $i++) {
//         yield $i;
//     }
// }

// $autoIncrement = autoIncrement();


// function randomDate($sStartDate, $sEndDate, $sFormat = 'Y-m-d')
// {
//     // Convert the supplied date to timestamp
//     $fMin = strtotime($sStartDate);
//     $fMax = strtotime($sEndDate);
//     // Generate a random number from the start and end dates
//     $fVal = mt_rand($fMin, $fMax);
//     // Convert back to the specified date format
//     return date($sFormat, $fVal);
// }

// function getRace() {        $races[] = "Caucasian";
//     $races[] = "African American";
//     $races[] = "Hispanic";
//     $races[] = "Asian";
//     $races[] = "Middle Eastern";
//     $raceIdx = rand(0,sizeof($races)-1);
//     return $races[$raceIdx];
// }

// function getGender() {
//     $genderIdx = rand(0,1);
//     $genderArr[0] = "male";
//     $genderArr[1] = "female";
//     return $genderArr[$genderIdx];
// }
