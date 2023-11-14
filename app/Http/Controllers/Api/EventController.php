<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load relationships and retrieve a query for events
        $query = $this->loadRelationships(Event::query());

        // Return a collection of EventResource objects for the paginated events
        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    public function store(Request $request)
    {
        // Create a new event with validated request data
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        // Return a new EventResource object for the created event
        return new EventResource($this->loadRelationships($event));
    }

    public function show(Event $event)
    {
        // Return an EventResource object for the specified event
        return new EventResource($this->loadRelationships($event));
    }

    public function update(Request $request, Event $event)
    {
        // Update the event with validated request data
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        // Return an EventResource object for the updated event
        return new EventResource($this->loadRelationships($event));
    }

    public function destroy(Event $event)
    {
        // Delete the specified event
        $event->delete();

        // Return a response with status code 204
        return response(status: 204);
    }
}