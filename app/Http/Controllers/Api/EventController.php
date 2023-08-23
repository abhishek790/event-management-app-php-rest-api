<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use \App\Models\Event;

// optionally loading some of the relations and other not 
//so to achieve this we'll be including relationships optionally by just passing a query parameter to the URL 
//  eg: {{BASE_RUL}}events?include=user,attendees,attendees.user
// so how can we  parse this include parameter to change it into an array and then how can we use this array of relationships to load specific relationships on demand
class EventController extends Controller
{

    public function index()
    {
        return EventResource::collection(Event::with('user', 'attendees')->paginate());

        // we will be doing the work inside the index method but we can also add a helper method
    }


    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($event);
    }


    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($event);
    }


    public function update(Request $request, Event $event)
    {


        $event->update([
            ...$request->validate([

                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($event);


    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}