<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Routing\Controllers\Middleware;

class ReservationController extends Controller
{
    /**
     * This property is used to handle various operations related to reservations,
     * such as creating, updating.
     *
     * @var ReservationService
     */
    protected $reservationService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view reservation', only: ['index','show']),
            new Middleware('permission:create reservation', only: ['store']),
            new Middleware('permission:edit reservation', only: ['update']),
            new Middleware('permission:delete reservation', only: ['destroy']),
        ];
    }

    /**
     * Constructor for the ReservationController class.
     * 
     * Initializes the $reservationService property via dependency injection.
     * 
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * This method return all reservations from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(Reservation::all());
    }

    /**
     * Register a new reservation in the database using the ReservationService via the createReservation method
     * passes the validated request data to createReservation.
     * 
     * @param StoreReservationRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReservationRequest $request)
    {
        return $this->success(
            $this->reservationService->createReservation($request->validated()), 'Reservation has been created successfully',
            201);
    }

    /**
     * Get reservation from database.
     * 
     * @param Reservation $reservation
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        return $this->success($reservation);
    }

    /**
     * Update a reservation in the database using the ReservationService via the updateReservation method.
     * passes the validated request data to updateReservation.
     * 
     * @param UpdateReservationRequest $request
     * 
     * @param Reservation $reservation
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        return $this->success($this->reservationService->updateReservation($request->validated(),$reservation),'updated successfuly');
    }

    /**
     * Delete reservation from database.
     * 
     * @param  Reservation $reservation
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy( Reservation $reservation)
    {
        $this->reservationService->deleteReservation($reservation);
        return $this->success(null,'Deleted successfuly', 204);
    }
}
