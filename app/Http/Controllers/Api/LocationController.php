<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class LocationController extends Controller
{
    /**
     * This property is used to handle various operations related to loactions,
     * such as creating, updating.
     *
     * @var LocationService
     */
    protected $locationService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view location', only: ['index','show']),
            new Middleware('permission:create location', only: ['store']),
            new Middleware('permission:edit location', only: ['update']),
            new Middleware('permission:delete location', only: ['destroy']),
        ];
    }

    /**
     * Constructor for the LocationController class.
     * 
     * Initializes the $locationService property via dependency injection.
     * 
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * This method return all loactions from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(Location::with('images')->get());
    }

    /**
     * Register a new Location in the database using the LocationService via the createLocation method
     * passes the validated request data to createLocation.
     * 
     * @param StoreLocationRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        return $this->success(
            $this->locationService->createLocation($request->validated()), 'Location has been created successfully',
            201);
    }

    /**
     * Get location from database.
     * 
     * @param Location $location
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return $this->success($location);
    }

    /**
     * Update a location in the database using the LocationService via the updateLocation method.
     * passes the validated request data to updateLocation.
     * 
     * @param UpdateLocationRequest $request
     * 
     * @param Location $location
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        return $this->success($this->locationService->updateLocation($request->validated(),$location),'updated successfuly');
    }

    /**
     * Delete location from database.
     * 
     * @param  Location $location
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy( Location $location)
    {
        $location->delete();
        return $this->success(null,'Deleted successfuly', 204);
    }

    /**
     * This method return all images associated with this location 
     * 
     * @param Location $location
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEventImages(Location $location)
    {
        return $this->success($this->locationService->getImages($location));
    }
}
