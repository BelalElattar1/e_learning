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

if (!function_exists('store_image_public')) {

    function store_image_public($file, $folder)
    {

        if($file) {
            $image = $file->store($folder, 'public');
            return $image;
        }
        return null;

    }

}