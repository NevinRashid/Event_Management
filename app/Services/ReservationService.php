<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Add new reservation to the database.
     * 
     * @param array $reservationdata
     * 
     * @return Reservation $reservation
     */
    public function createReservation(array $data)
    {
        try{
            if(Auth::user()->organizedEvents()->where('id', $data['event_id'])->exists()
                || Auth::user()->hasAnyRole(['super admin','admin','attendee']))
                {
                    $event= Event::withCount('attendees')->findOrfail($data['event_id']);
                    if($event->attendees_count >= $event->capacity){
                        return response()->json(['meassage' => 'Event is fully booked'],400);
                    }
                }
            return Reservation::create($data);

        }catch(\Throwable $th){

        }    
    }

    /**
     * Update the specified reservation in the database.
     * 
     * @param array $reservationdata
     * @return Reservation $reservation
     * 
     * @return Reservation $reservation
     */
    public function updateReservation(array $data,Reservation $reservation){
        try{

            if(Auth::user()->organizedEvents()->where('id', $reservation->event_id)->exists()
                || Auth::user()->hasAnyRole(['super admin','admin'])
                || Auth::user()->id === $reservation->user_id
            )
            $reservation->update(array_filter($data));
            return $reservation;
        }catch(\Throwable $th){
            
        }
    }

    /**
     * Delete reservation from the database.
     * 
     * @param Reservation $reservation
     * 
     * @return void
     */
    public function deleteReservation(Reservation $reservation)
    {
        try{
            if(Auth::user()->organizedEvents()->where('id', $reservation->event_id)->exists()
                || Auth::user()->hasRole('super admin')
                || Auth::user()->hasRole('admin')
                || Auth::user()->id === $reservation->user_id
            )
            $reservation->delete();
            
        }catch(\Throwable $th){

        }
}
}