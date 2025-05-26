<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ImageController extends Controller
{
    /**
     * This property is used to handle various operations related to images,
     * such as uploading, updating.
     *
     * @var ImageService
     */
    protected $imageService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view image', only: ['index','show']),
            new Middleware('permission:upload image', only: ['store']),
            new Middleware('permission:update image', only: ['update']),
            new Middleware('permission:delete image', only: ['destroy']),
        ];
    }

    /**
     * Constructor for the ImageController class.
     * 
     * Initializes the $imageService property via dependency injection.
     * 
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * This method return all images from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(Image::paginate(20));
    }

    /**
     * Add new image in the database using the ImageService via the createImage method
     * passes the validated request data to createImage.
     * 
     * @param StoreImageRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        $validatedData=$request->validated();
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $path = UploadImage($file,'imgs');
            $validatedData['path'] = $path;
        }
            return $this->success(
            $this->imageService->addImage($validatedData), 'Image has been uploaded successfully',
            201);
    }

    /**
     * Get image from database.
     * 
     * @param Image $image
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return $this->success($image);
    }

    /**
     * Update an image in the database using the ImageService via the updateImage method.
     * passes the validated request data to updateImage.
     * 
     * @param UpdateImageRequest $request
     * 
     * @param Image $image
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        $validatedData=$request->validated();
        if ($request->hasFile('path')) {
            $file=$request->file('path');
            $path = UploadImage($file, "imgs");
            $validatedData['path'] = $path;
        }
        return $this->success($this->imageService->updateImage($request->validated(),$image),'updated successfuly');
    }

    /**
     * Delete image from database.
     * 
     * @param  Location $location
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy( Image $image)
    {
        $image->delete();
        return $this->success(null,'Deleted successfuly', 204);
    }
}
