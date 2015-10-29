<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 29/10/2015
 * Time: 11:48
 */

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}


/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * Validating latitude and longitude
 */
function validateGeolocation($lat, $lng) {
    $app = \Slim\Slim::getInstance();
    if(is_numeric($lat) && is_numeric($lng)){
        if($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180){
            $response["error"] = true;
            $response["message"] = 'The latitude must be a number between -90 and 90 and the longitude between -180 and 180';
            echoResponse(400, $response);
            $app->stop();
        }
    }
    else{
        $response["error"] = true;
        $response["message"] = 'Latitude and Longitude must be numeric values';
        echoResponse(400, $response);
        $app->stop();
    }
}


/**
 * Validating score value
 */
function validateScore($score) {
    $app = \Slim\Slim::getInstance();
    if(is_numeric($score)){
        if($score < 0 || $score > 5){
            $response["error"] = true;
            $response["message"] = 'The score must be a number between 0 and 5';
            echoResponse(400, $response);
            $app->stop();
        }
    }
    else{
        $response["error"] = true;
        $response["message"] = 'Score must be numeric value';
        echoResponse(400, $response);
        $app->stop();
    }
}


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

?>