<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$autoIncrement = autoIncrement();

$factory->define(App\User::class, function (Faker $faker) {
    
    $firstName = $faker->firstName;
    
    $lastName = $faker->lastName;
    
    $name = $firstName . ' ' . $lastName;

    $domain = "example.org";

    $email = strtolower($firstName[0] . "." . $lastName . "@" . $domain);

    $now = now();

    return [
        // 'name' => $faker->name,
        // 'password' => $password ?: $password = bcrypt('q'),

        'email' => $email,     
        'name' => $name, 
        'last_name' => $lastName,   
        'password' => '$2y$10$v7UA4cNXzQYJFQgBSa2eo.2bl5xc29CcTv24ZiJg3Xa.1EfE.jU4W',   
        'role_id' => 4,               
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
