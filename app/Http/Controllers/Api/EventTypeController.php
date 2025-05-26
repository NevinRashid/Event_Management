<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event_Type\StoreEventTypeRequest;
use App\Http\Requests\Event_Type\UpdateEventTypeRequest;
use App\Models\EventType;
use App\Services\EventTypeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class EventTypeController extends Controller
{
    /**
     * This property is used to handle various operations related to event types,
     * such as creating, updating.
     *
     * @var EventTypeService
     */
    protected $eventTypeService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view event-type', only: ['index','show']),
            new Middleware('permission:create event-type', only: ['store']),
            new Middleware('permission:edit event-type', only: ['update']),
            new Middleware('permission:delete event-type', only: ['destroy']),
        ];
    }

    /**
     * Constructor for the EventTypeController class.
     * 
     * Initializes the $eventTypeService property via dependency injection.
     * 
     * @param EventTypeService $eventTypeService
     */
    public function __construct(EventTypeService $eventTypeService)
    {
        $this->eventTypeService = $eventTypeService;
    }

    /**
     * This method return all event types from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(EventType::all());
    }

    /**
     * Register a new event type in the database using the EventTypeService via the createEventType method
     * passes the validated request data to createEventType.
     * 
     * @param StoreEventTypeRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventTypeRequest $request)
    {
        return $this->success(
            $this->eventTypeService->createEventType($request->validated()), 'Event type has been created successfully',
            201);
    }

    /**
     * Get event type from database.
     * 
     * @param EventType $eventType
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(EventType $eventType)
    {
        return $this->success($eventType);
    }

    /**
     * Update a event type in the database using the EventTypeService via the updateEventType method.
     * passes the validated request data to updateEventType.
     * 
     * @param UpdateEventTypeRequest $request
     * 
     * @param EventType $eventType
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventTypeRequest $request, EventType $eventType)
    {
        return $this->success($this->eventTypeService->updateEventType($request->validated(),$eventType),'updated successfuly');
    }

    /**
     * Delete event type from database.
     * 
     * @param  EventType $eventType
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy( EventType $eventType)
    {
        $eventType->delete();
        return $this->success(null,'Deleted successfuly', 204);
    }
}
