<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return parent::toArray($request);
    }
    // this class is not directly tied to the event model or event controller,so we will have to use those resource classes explicitly,we can use ti for an event model and moreover you can have many resource classes for one single model in case you would like to serialize it to,different kind of outputs
}