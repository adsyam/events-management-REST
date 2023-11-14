<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;

class AttendeeController extends Controller
{

    public function index(Event $event)
    {
        $attendee = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendee->paginate()
        );
    }


    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }


    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();
    
        return response(status: 204);
    }
}
