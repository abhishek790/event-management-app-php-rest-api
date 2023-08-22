<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\AttendeeResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    // API resources is a way to customize how your data from your eloquent models is being transformed to json responses ,now laravel can convert eloquent models to json without using the api resources.By default, it would use a toJson() method which is on every single eloquent model but to have more control we use api resource
    public function toArray(Request $request): array
    { //here we implement how specific resource should be converted to json
        // return parent::toArray($request);

        return [
            // lets return an array,we can customize the attribute names of whatever is being returned from the API,so the json returned does not need to be identical to what we have inside the eloquent model,now you have got all the model data inside this,so you can use this ID and this would be matched 1 to 1 to the attributes from the model
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,

            // every event has this user_id column which points to the owner of a specific event(organizer), so you when getting all the events you might want to display also the organizer name, if you have 100 or 200 events returned at once, that would be inefficient to fetch all the events and then try to make let's say 100 request for every single user just to display the organizer of the event, so we can build some nested resources inside such event resource by conditionally loading the user

            // we add an optional field called user, but we won't be passing the user that's associated with this event instead we will use the magical method of the resource called whenloaded() and pass the name of the relationship(it is between Event model and User model)
            // what happens here is the user property would only be present on the response if this user relationship of particular event is loaded for eg: EventResource::collection(Event::with('user')->get());
            'user' => new UserResource($this->whenLoaded('user')),
            'attendee' => AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )

        ];

    }
    // this class is not directly tied to the event model or event controller,so we will have to use those resource classes explicitly,we can use it for an event model and moreover you can have many resource classes for one single model in case you would like to serialize it to,different kind of outputs
}