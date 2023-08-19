<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //we know that every event needs to be owned by a user, so not only we need to generate a new event using the event factory every event needs to be tied to a specific user,
        // so the first step is to get all the user form the database
        $user = User::all();

        // we have got all the user and now we generate 200 events
        for ($i = 0; $i < 200; $i++) {
            // lets get random user. This User::all() returns a collection that's an object in laravel,laravel has a nice built in library for handling collections which basically is  built on top of arrays,it gives you a nice object oriented interface to manipulate collections of things
            $user = $user->random();
            // this create() would create a data and store it in db, but in case of this one we need to pass the user ID ,this would be id of user chosen by random method
            \App\Models\Event::factory()->create([
                'user_id' => $user->id
            ]);
        }

    }
}