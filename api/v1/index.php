<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 28/10/2015
 * Time: 11:24
 */

require_once '../include/db_handler.php';
require_once '../include/validation.php';
require '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * Listing single place
 * url /place/:id
 * method GET
 * Will return 404 if the place doesn't exist
 */
$app->get('/place/:id', function($place_id) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getPlaceById($place_id);

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
        $result = $db->getScoreByPlaceId($place_id);

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
 * Listing score
 * url /place/:id
 * method GET
 * Will return 404 if the score doesn't exist
 */
$app->get('/score/:id', function($place_id) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getScoreByPlaceId($place_id);

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


/**
 * Score Registration
 * url - /score
 * method - POST
 * params - place_id, paper, size, wait_time, cleanliness, smell
 * Return: id of the new added score, or 400 if there are any error
 */
$app->post('/score', function() use ($app) {
    verifyRequiredParams(array('place_id', 'paper', 'size', 'wait_time', 'cleanliness', 'smell'));

    $response = array();

    // reading post params
    $place_id = $app->request->post('place_id');
    $paper = $app->request->post('paper');
    $size = $app->request->post('size');
    $wait_time = $app->request->post('wait_time');
    $cleanliness = $app->request->post('cleanliness');
    $smell = $app->request->post('smell');

    validateScore($paper);
    validateScore($size);
    validateScore($wait_time);
    validateScore($cleanliness);
    validateScore($smell);

    $db = new db_handler();
    $result = $db->addScore($place_id, $paper, $size, $wait_time, $cleanliness, $smell);

    if(!$result){
        $response["error"] = true;
        $response["message"] = "Sorry, an error has occurred adding the score";
        echoResponse(400, $response);
    }
    else{
        $response["error"] = false;
        $response["message"] = "Score added successfully";
        $response["score_id"] = $result;
        echoResponse(200, $response);
    }

});


/**
 * Listing comments
 * url /comments/:id
 * method GET
 * Will return 404 if the are any comment
 */
$app->get('/comments/:id', function($place_id) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getCommentsByPlaceId($place_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["comments"] = $result;

        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested place doesn't have any comment";
        echoResponse(404, $response);
    }
});

/**
 * Comment Registration
 * url - /comment
 * method - POST
 * params - place_id, comment
 * Return: id of the new added comment, or 400 if there are any error
 */
$app->post('/comment', function() use ($app) {
    verifyRequiredParams(array('place_id', 'comment'));

    $response = array();

    // reading post params
    $place_id = $app->request->post('place_id');
    $comment = $app->request->post('comment');

    $db = new db_handler();
    $result = $db->addComment($place_id, $comment);

    if(!$result){
        $response["error"] = true;
        $response["message"] = "Sorry, an error has occurred adding the comment";
        echoResponse(400, $response);
    }
    else{
        $response["error"] = false;
        $response["message"] = "Comment added successfully";
        $response["comment_id"] = $result;
        echoResponse(200, $response);
    }

});


/**
 * Listing last added places
 * url /lastAddedPlaces
 * method GET
 * Will return 404 if the are any place
 */
$app->get('/lastAddedPlaces', function() {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getLastAddedPlaces();

    if ($result != NULL) {
        $response["error"] = false;
        $response["places"] = $result;

        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "There are not recent places";
        echoResponse(404, $response);
    }
});


/**
 * Get the total number of places
 * url /numberOfPlaces
 * method GET
 * Will return 404 if the are not any place
 */
$app->get('/numberOfPlaces', function() {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getNumberOfPlaces();

    if ($result != NULL) {
        $response["error"] = false;
        $response["number_of_places"] = $result["COUNT(*)"];
        echoResponse(200, $response);
        $response["error"] = true;
    } else {
        $response["message"] = "The are not any place";
        echoResponse(404, $response);
    }
});


/**
 * Most visited places
 * url /topVisitedPlaces
 * method GET
 * Will return 404 if the are any place
 */
$app->get('/topVisitedPlaces', function() {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getMostVisitedPlaces();

    if ($result != NULL) {
        $response["error"] = false;
        $response["places"] = $result;

        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "There are not recent places";
        echoResponse(404, $response);
    }
});


/**
 * Top rated places
 * url /topRatedPlaces
 * method GET
 * Will return 404 if the are any place
 */
$app->get('/topRatedPlaces', function() {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getTopRatedPlaces();

    if ($result != NULL) {
        $response["error"] = false;
        $response["places"] = $result;

        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "There are not any place";
        echoResponse(404, $response);
    }
});


/**
 * Photo upload
 * url - /photo
 * method - POST
 * params - file, place_id
 * Return: id of the new photo added, or 400 if there are any error
 */
$app->post('/photo', function() use ($app) {

    verifyRequiredParams(array('place_id'));

    if(isset($_FILES["photo"])){
        $target_dir = "../../images/";
        $file_type = pathinfo(basename($_FILES["photo"]["name"]),PATHINFO_EXTENSION);
        $file_id =  md5(uniqid(rand(), true));
        $target_file = $target_dir . $file_id . "." . $file_type;

        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            // Check if file already exists
            if (file_exists($target_file)) {
                $response["error"] = true;
                $response["message"] = "File already exists";
                echoResponse(400, $response);
            }
            else{
                // Check file size
                if ($_FILES["photo"]["size"] > 10485760) {
                    $response["error"] = true;
                    $response["message"] = "Your file is too large. The maximum size is 10 MB";
                    echoResponse(400, $response);
                }
                else{
                    // Allow certain file formats
                    if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ) {
                        $response["error"] = true;
                        $response["message"] = "Only JPG, JPEG, PNG & GIF files are allowed";
                        echoResponse(400, $response);
                    }
                    else{
                        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                            $response = array();

                            // reading post params
                            $place_id = $app->request->post('place_id');

                            $db = new db_handler();
                            $result = $db->addPhoto($place_id, $file_id . "." . $file_type);

                            if(!$result){
                                $response["error"] = true;
                                $response["message"] = "There was an error uploading your file";
                                echoResponse(400, $response);
                            }
                            else{
                                $response["error"] = false;
                                $response["message"] = "Photo added successfully";
                                $response["file"] = $file_id . "." . $file_type;
                                echoResponse(200, $response);
                            }
                        } else {
                            $response["error"] = true;
                            $response["message"] = "There was an error uploading your file";
                            echoResponse(400, $response);
                        }
                    }
                }
            }
        } else {
            $response["error"] = true;
            $response["message"] = "File is not an image";
            echoResponse(400, $response);
        }
    }
    else{
        $response["error"] = true;
        $response["message"] = "Required file photo is missing or empty";
        echoResponse(400, $response);
    }
});


/**
 * Listing photos from a place
 * url /photos/:place_id
 * method GET
 * Will return 404 if the are any photo
 */
$app->get('/photos/:place_id', function($place_id) {
    $response = array();
    $db = new db_handler();

    // fetch task
    $result = $db->getPhotosByPlaceId($place_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["photos"] = $result;

        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested place doesn't have any photo";
        echoResponse(404, $response);
    }
});



$app->run();
?>