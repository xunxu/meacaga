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
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/score', function() use ($app) {

    $response = array();

    // reading post params
    $placeId = $app->request->post('PlaceId');

    $db = new db_handler();
    $res = $db->createScore($placeId);

    $response["error"] = true;
    $response["message"] = "Sorry, this email already existed index";
    echoResponse(200, $response);

});


$app->run();
?>