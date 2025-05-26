<?php

namespace App\Services;

use App\Models\Image;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Add new image to the database.
     * 
     * @param array $imagedata
     * 
     * @return Image $image
     */
    public function addImage(array $data)
    {
        try{
            return Image::create($data);
        }catch(\Throwable $th){

        }    
    }

    /**
     * Update the specified image in the database.
     * 
     * @param array $imagedata
     * @param Image $image
     * 
     * @return Image $image
     */
    public function updateImage(array $data, Image $image){
        try{
            
            $image->update(array_filter($data));
            return $image;
        }catch(\Throwable $th){
            
        }
    }
}
