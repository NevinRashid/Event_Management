<?php

namespace App\Services;

use App\Models\EventType;

class EventTypeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Add new event type to the database.
     * 
     * @param array $eventTypedata
     * 
     * @return EventType $eventType
     */
    public function createEventType(array $data)
    {
        try{
            return EventType::create($data);

        }catch(\Throwable $th){

        }    
    }

    /**
     * Update the specified event type in the database.
     * 
     * @param array $eventTypedata
     * @param EventType $eventType
     * 
     * @return EventType $eventType
     */
    public function updateEventType(array $data, EventType $eventType){
        try{
            
            $eventType->update(array_filter($data));
            return $eventType;
        }catch(\Throwable $th){
            
        }
    }

}
