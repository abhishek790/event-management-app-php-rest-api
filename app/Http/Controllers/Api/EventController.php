<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationship;
use Illuminate\Http\Request;
use \App\Models\Event;


class EventController extends Controller
{
    use CanLoadRelationship;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('throttle:60,1')->only(['store', 'update', 'destroy']);
        $this->authorizeResource(Event::class, 'event');

    }


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
            'user_id' => $request->user()->id
        ]);

        return new EventResource($this->loadRelationship($event));
    }
    public function show(Event $event)
    {
        return new EventResource($this->loadRelationship($event));
    }

    public function update(Request $request, Event $event)
    {

        // $this->authorize('update-event', $event);
        $event->update([
            ...$request->validate([

                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ]),
            'user_id' => $request->user()->id
        ]);
        return new EventResource($this->loadRelationship($event));
    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}

//building system that will remind attendees that the event they are going to attend is less than 24 hours away
// customs artison command
// task scheduling
// notification/emails
// queues=> it allows you to defer some time consuming tasks to run in the background and this general will increase you application performance and the user experience.
// form some time consuming task you assign laravel to do it later and then a separate process running on the server called the queue worker wll just pick up tasks that are stored in different storages like(redis) and then it will execute in the background

// create a table to hold the queue jobs
// php artisan queue:table => after this run php artisan migrate
