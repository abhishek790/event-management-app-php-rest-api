<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Event;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // this is signature comes after php artisan (here you write you signature)
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */

    //  this will display in list of command when you write php aritsan
    protected $description = 'Sends notification to all event attendees, that event starts soons';

    /**
     * Execute the console command.
     */
    // logic is implemented here when this command is executed
    public function handle()
    {
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();


        //  returns how many events are found
// since $events is a collection which is just a wrapper on top of array,which enables adding some method to it
        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found $eventCount $eventLabel.");

        $events->each(
            fn($event) => $event->attendees
                ->each(
                    fn($attendee) => $this->info("Notifying the user {$attendee->user->id}")
                )
        );

        $this->info('Reminder notification sent successfully');
    }
}
