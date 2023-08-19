<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //we would also need users as we need people that will attend the events
        $users = User::all();

        // now every user will attend some random set of events,for that we first need to get all the events
        $events = Event::all();

        // now for every user we will make this user attend some random amount of events
        foreach ($users as $user) {
            // from the events collection we will choose a random number of events using the rand function to generate a random number between 1 and 3
            $eventsToAttend = $events->random(rand(1, 3));

            foreach ($eventsToAttend as $event) {
                // there is no factory for the attendee as everything the attendee holds is a relationship to the user which would attend the event and to the actual event,thus we can pass a simple array where there would be a user ID,and event ID
                \App\Models\Attendee::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);

            }

        }
    }
}