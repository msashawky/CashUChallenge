<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api'], function () use ($router) { //Grouping All Routes The Under API Prefix

    /*************************** End of Hotel Searching ***************************/

        $router->group(['prefix' => 'hotel'], function () use ($router) {
            $router->get('myFollowingPosts', 'PostController@homePosts');

            $router->post('/search', 'HotelController@search');
            $router->get('/getUserData', 'OrganizationController@getUserData');
            $router->get('/getUserDataByCode/{code}', 'OrganizationController@getUserDataByCode');

        });

    /*************************** End of Hotel Searching ***************************/




});
