<?php
    function uploadImage($file, $folder, $disk = 'public')
    {
        $image = time() . $file->getClientOriginalName();
        $path = $file->storeAs($folder, $image, $disk);
        return $path;
    }
?>
