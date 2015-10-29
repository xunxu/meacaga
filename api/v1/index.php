<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 28/10/2015
 * Time: 11:24
 */

require_once '../include/db_handler.php';
require_once '../include/validation.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * Listing single place
 * url /place/:id
 * method GET
 * Will return 404 if the place doesn't exist
 */
$app->get('/place/:id', function($placeId) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getPlaceById($placeId);

    if ($result != NULL) {
        $response["error"] = false;
        $response["name"] = $result["name"];
        $response["description"] = $result["description"];
        $response["address"] = $result["address"];
        $response["lat"] = $result["lat"];
        $response["lng"] = $result["lng"];
        $response["author"] = $result["author"];
        $response["email"] = $result["email"];
        $response["date"] = $result["date"];
        $result = $db->getScoreByPlaceId($placeId);

        if ($result != NULL) {
            $response["error"] = false;
            $response["paper"] = $result["paper"];
            $response["size"] = $result["size"];
            $response["wait_time"] = $result["wait_time"];
            $response["cleanliness"] = $result["cleanliness"];
            $response["smell"] = $result["smell"];
            echoResponse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "The requested place doesn't have any score";
            echoResponse(404, $response);
        }
    } else {
        $response["error"] = true;
        $response["message"] = "The requested place doesn't exists";
        echoResponse(404, $response);
    }
});

/**
 * Place Registration
 * url - /place
 * method - POST
 * params - name, description, address, lat, lng, author, email
 * Return: id of the new added place, or 400 if there are any error
 */
$app->post('/place', function() use ($app) {
    verifyRequiredParams(array('name', 'description', 'address', 'lat', 'lng', 'author', 'email'));

    $response = array();

    // reading post params
    $name = $app->request->post('name');
    $description = $app->request->post('description');
    $address = $app->request->post('address');
    $lat = $app->request->post('lat');
    $lng = $app->request->post('lng');
    $author = $app->request->post('author');
    $email = $app->request->post('email');

    validateGeolocation($lat, $lng);
    validateEmail($email);

    $db = new db_handler();
    $result = $db->addPlace($name, $description, $address, $lat, $lng, $author, $email);

    if(!$result){
        $response["error"] = true;
        $response["message"] = "Sorry, an error has occurred adding the place";
        echoResponse(400, $response);
    }
    else{
        $response["error"] = false;
        $response["message"] = "Place added successfully";
        $response["place_id"] = $result;
        echoResponse(200, $response);
    }

});



/**
 * Listing single place
 * url /place/:id
 * method GET
 * Will return 404 if the place doesn't exist
 */
$app->get('/score/:id', function($placeId) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getScoreByPlaceId($placeId);

    if ($result != NULL) {
        $response["error"] = false;
        $response["paper"] = $result["paper"];
        $response["size"] = $result["size"];
        $response["wait_time"] = $result["wait_time"];
        $response["cleanliness"] = $result["cleanliness"];
        $response["smell"] = $result["smell"];
        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested place doesn't have any score";
        echoResponse(404, $response);
    }
});



$app->run();
?>