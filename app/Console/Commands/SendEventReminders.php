<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
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

    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */


    protected $description = 'Sends notification to all event attendees, that event starts soons';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found $eventCount $eventLabel.");
        $events->each(
            fn($event) => $event->attendees
                ->each(
                    fn($attendee) => $attendee->user->notify(
                        new EventReminderNotification($event)
                    )
                )
        );

        $this->info('Reminder notification sent successfully');
    }
}

// there needs to separate process running on your server that go to the database pickup those jobs to be run and execute them one by one, so this means we have to run some additional command that needs to be constantly working and it needs to grab those items from the queue and execute them as they come in those item, for that you need to run [php artisan queue:work] this command runs all the time next to your actual web server