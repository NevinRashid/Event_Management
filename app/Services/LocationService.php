<?php

namespace App\Services;

use App\Models\Location;

class LocationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Add new location to the database.
     * 
     * @param array $locationdata
     * 
     * @return Location $location
     */
    public function createLocation(array $data)
    {
        try{
            $location = Location::create($data);
            if (!empty($data['path'])) {
                $location->images()->create(['path' => $data['path']]);
            }
            return $location;

        }catch(\Throwable $th){

        }    
    }

    /**
     * Update the specified location in the database.
     * 
     * @param array $locationdata
     * @param Location $location
     * 
     * @return Location $location
     */
    public function updateLocation(array $data, Location $location){
        try{
            $location->update(array_filter($data));
                if (!empty($data['path'])) {
                    $location->images()->create(['path' => $data['path']]);
                }
            return $location;
        }catch(\Throwable $th){
            
        }
    }

    /**
     * This method return all images associated with this location from database.
     * 
     * @param Location $location
     * 
     * @return Image $imagesarray
     */
    public function getImages(Location $location)
    {
        try{
            return $location->images();
        }catch(\Throwable $th){

        }        
    }
}
