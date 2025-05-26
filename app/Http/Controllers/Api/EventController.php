<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class EventController extends Controller
{
    
    /**
     * This property is used to handle various operations related to events,
     * such as creating, updating.
     *
     * @var EventService
     */
    protected $eventService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view event', only: ['index','show']),
            new Middleware('permission:create event', only: ['store']),
            new Middleware('permission:edit event', only: ['update']),
            new Middleware('permission:delete event', only: ['destroy']),
            new Middleware('permission:get reservations', only: ['getEventReservations']),
            new Middleware('permission:view image', only: ['getEventReservations']),
        ];
    }

    /**
     * Constructor for the EventController class.
     * 
     * Initializes the $eventService property via dependency injection.
     * 
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * This method return all events s from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(Event::all());
    }

    /**
     * Register a new event in the database using the EventService via the createEvent method
     * passes the validated request data to createEvent.
     * 
     * @param StoreEventRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        return $this->success(
            $this->eventService->createEvent($request->validated()), 'Event  has been created successfully',
            201);
    }

    /**
     * Get event  from database.
     * 
     * @param Event $event
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return $this->success($event);
    }

    /**
     * Update a event  in the database using the EventService via the updateEvent method.
     * passes the validated request data to updateEvent.
     * 
     * @param UpdateEventRequest $request
     * 
     * @param Event $event
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        return $this->success($this->eventService->updateEvent($request->validated(),$event),'updated successfuly');
    }

    /**
     * Delete event  from database.
     * 
     * @param  Event $event
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy( Event $event)
    {
        $event->delete();
        return $this->success(null,'Deleted successfuly', 204);
    }

    /**
     * This method return all resrvations associated with this event 
     * with the user who owns this reservation from database.
     * 
     * @param Event $event
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEventReservations(Event $event)
    {
        return $this->success($this->eventService->getReservations($event));
    }

    /**
     * This method return all images associated with this event 
     * 
     * @param Event $event
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEventImages(Event $event)
    {
        return $this->success($this->eventService->getImages($event));
    }

}
