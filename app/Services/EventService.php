<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Getusers according to the role of the request holder the database.
     * This method fetches users based on the role of the currently authenticated user.
     * - If the user has the "super admin" role, all users will be returned.
     * - If the user has the "admin" role, only users with "organizer" or "attendee" roles will be returned.
     *    
     * @return Event $arrayevents
     */
    public function getEvents()
    {
        try{
            if(Auth::user()->hasAnyRole(['super admin','admin'])){
                $events= Event::withoutGlobalScopes()->paginate(20);
                return $events;
            }
            else{
                $events= Event::paginate(20);
                return $events;
            }

        }catch(\Throwable $th){

        }    
    }

    /**
     * This method return all resrvations associated with this event 
     * with the user who owns this reservation from database.
     * 
     * @param Event $event
     * 
     * @return Reservation $arrayreservations
     */
    public function getReservations(Event $event)
    {
        try{
            return $event->reservations()->with('user');
        }catch(\Throwable $th){

        }        
    }

    /**
     * This method return all images associated with this event from database.
     * 
     * @param Event $event 
     * 
     * @return Image $imagesarray
     */
    public function getImages(Event $event)
    {
        try{
            return $event->images();
        }catch(\Throwable $th){

        }        
    }

    /**
     * Add new event  to the database.
     * 
     * @param array $eventdata
     * 
     * @return Event $event
     */
    public function createEvent(array $data)
    {
        try{
            $user = Auth::user();
            $data['created_by'] = $user->id;
            $event = Event::create($data);
            if (!empty($data['path'])) {
                $event->images()->create(['path' => $data['path']]);
            }
            return $event;

        }catch(\Throwable $th){

        }    
    }

    /**
     * Update the specified event in the database.
     * 
     * @param array $eventdata
     * @param Event $event
     * 
     * @return Event $event
     */
    public function updateEvent(array $data, Event $event){
        try{
            $user = Auth::user();
            if($event->organizer_id == $user->id 
                || Auth::user()->hasAnyRole(['super admin','admin'])
                ){
                    $event->update(array_filter($data));
                    if (!empty($data['path'])) {
                        $event->images()->create(['path' => $data['path']]);
                    }
                    return $event;
                }
        }catch(\Throwable $th){
            
        }
    }

    /**
     * This method return all events that have images attached.
     *     
     * @return Event $eventsarray
     */
    public function eventsWithImages()
    {
        try{
            $events = Event::whereHas('images')->get();
            return $events;
        }catch(\Throwable $th){

        }        
    }

}
