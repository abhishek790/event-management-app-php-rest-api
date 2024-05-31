<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationship;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;

class AttendeeController extends Controller
{
    use CanLoadRelationship;
    private array $relations = ['user', 'event', 'event.user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->authorizeResource(Attendee::class, 'attendee');
    }

    // api/events/{event}/attendees 
    public function index(Event $event)
    {
        $attendee = $this->loadRelationship($event->attendees()->latest());


        return AttendeeResource::collection($attendee->paginate());
    }

    // api/events/{event}/attendees
    public function store(Request $request, Event $event)
    {

        $attendee = $event->attendees()->create(

            ['user_id' => 1]
        );
        return new AttendeeResource($this->loadRelationship($attendee));
    }

    // api/events/{event}/attendees/{attendee}
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($this->loadRelationship($attendee));
    }


    // api/events/{event}/attendees/{attendee}
    public function destroy(Event $event, Attendee $attendee)
    {

        // $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();
        return response(status: 204);
    }
}