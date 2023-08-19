<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1000)->create();
        // to call the other seeder we need to that in specific order,so 1st we need to generate the all the events this is done by using call() and passing the class name of the seeder and then call attendee
        $this->call(EventSeeder::class);
        $this->call(AttendeeSeeder::class);
        // don't need to import those seeder classes as they are from the same namespace as the databasae seeder

        // summary
        // we load some users, we generate some events with a random owner and then we generate some attendees for that event. Every user attends a random amount of 1 to 3 events
    }
}