<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Traits\LoadIncludeRelationships;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{

    use LoadIncludeRelationships;

    private $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('throttle:api')
            ->only(['store', 'destroy']);
        $this->authorizeResource(Event::class, 'event');

        // Gate::authorize('update', $post);
    }


    public function index()
    {

        // return EventResource::collection(Event::all());
        // return EventResource::collection(Event::with('user', 'attendees')->paginate());

        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'start_time' => 'required|date',
        //     'end_time' => 'required|date|after:start_time',
        // ]);

        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]),
            'user_id' => $request->user()->id
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    public function show(Event $event)
    {
        // $event->load('user', 'attendees');
        return new EventResource($this->loadRelationships($event));
    }

    public function update(Request $request, Event $event)
    {
        // if(Gate::denies('update-event', $event)) {
        //     abort(403, 'Not authorized to update this event');
        // }

        $this->authorize('update-event', $event);

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ])
        );

        return new EventResource($this->loadRelationships($event));
    }

    public function destroy(Event $event)
    {
        $event->delete();

        // return response()->json([
        //     "message" => "Event deleted successfully"
        // ]);
        return response(status: 204);
    }
}
