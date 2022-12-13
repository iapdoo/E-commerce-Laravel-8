<?php

use Illuminate\Support\Facades\Config;

function get_language()
{
   return \App\Models\Language::Active()->Selection()->get();
}

function get_default_language()
{
    return Config::get('app.locale');
}

function UploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}
