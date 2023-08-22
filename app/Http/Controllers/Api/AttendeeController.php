<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;

class AttendeeController extends Controller
{

    // api/events/{event}/attendees 
    public function index(Event $event)
    {
        $attendee = $event->attendees()->latest();
        return AttendeeResource::collection($attendee->paginate());
    }

    // api/events/{event}/attendees
    public function store(Request $request, Event $event)
    {

        $attendee = $event->attendees()->create(

            ['user_id' => 1]
        );
        return new AttendeeResource($attendee);
    }

    // api/events/{event}/attendees/{attendee}
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }


    // api/events/{event}/attendees/{attendee}
    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();
        return response(status: 204);
    }
}