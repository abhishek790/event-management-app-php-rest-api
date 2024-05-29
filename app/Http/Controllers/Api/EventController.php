<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationship;
use Illuminate\Http\Request;
use \App\Models\Event;


class EventController extends Controller
{
    use CanLoadRelationship;


    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function index()
    {


        $query = $this->loadRelationship(Event::query());

        return EventResource::collection(
            $query->latest()->paginate()
        );



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

        return new EventResource($this->loadRelationship($event));
    }


    public function show(Event $event)
    {

        return new EventResource($this->loadRelationship($event));
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

        return new EventResource($this->loadRelationship($event));


    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}