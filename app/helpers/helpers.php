<?php

use App\Models\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;

//handle config path for lumen
if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }


//get url of json file
    function jsonUrl()
    {
        return storage_path() . "\json\hotels.json";
    }
}






