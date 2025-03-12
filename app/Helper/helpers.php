<?php

if (!function_exists('store_image')) {

    function store_image($file, $folder)
    {

        if($file) {
            $image = $file->store($folder, 'private');
            $image = str_replace("$folder/", '', $image);
            return $image;
        }
        return null;

    }

}