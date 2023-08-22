<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use \App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { //we will modify the index action so the resource class can be used both to return a collection of specific resources where every element on this collection will be converted using this toarray() method or just one single resource

        // now you would use this resource class for collection by typing the event resource,it has a static collection method to which you will just pass an array of resources which is a result of Event::all()
        return EventResource::collection(Event::with('user', 'attendees')->get());
        // api resources allow you to have more fields to add some kind to meta fields
        //note: yo garda data haru json bhane field ma wrap hunxa { "data":[] }
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    { // to return single request you would use this event resource,but this time creating a new instance of it
        // if you already have event then you can do this to load user
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}