<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 28/10/2015
 * Time: 11:24
 */

require_once '../include/db_handler.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;


/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}




/**
 * Listing single place
 * method GET
 * url /place/:id
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
        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested place doesn't exists";
        echoResponse(404, $response);
    }
});

/**
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/place', function() use ($app) {

    $response = array();

    // reading post params
    $name = $app->request->post('name');
    $description = $app->request->post('description');
    $address = $app->request->post('address');
    $lat = $app->request->post('lat');
    $lng = $app->request->post('lng');
    $author = $app->request->post('author');
    $email = $app->request->post('email');
    $date = $app->request->post('date');

    $db = new db_handler();
    $res = $db->addPlace($name, $description, $address, $lat, $lng, $author, $email, $date);

    $response["error"] = true;
    $response["message"] = "Sorry, an error has ocurred inserting the place";
    echoResponse(200, $response);

});


$app->run();
?>