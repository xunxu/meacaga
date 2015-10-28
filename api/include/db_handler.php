<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 28/10/2015
 * Time: 11:30
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 */


class db_handler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . './db_connect.php';
        // opening db connection
        $db = new db_connect();
        $this->conn = $db->connect();
    }

    /* ------------- `places` table method ------------------ */

    /**
     * Add new place
     * @param $name
     * @param $description
     * @param $address
     * @param $lat
     * @param $lng
     * @param $author
     * @param $email
     * @param $date
     */
    public function addPlace($name, $description, $address, $lat, $lng, $author, $email, $date) {
        $response = array();

        // insert query
        $stmt = $this->conn->prepare("INSERT INTO places(name, description, address, lat, lng, author, email, date) values(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss", $name, $description, $address, $lat, $lng, $author, $email, $date);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    /**
     * Get place by place ID
     * @param String $placeId place ID
     * @return place
     */
    public function getPlaceById($placeId) {
        $stmt = $this->conn->prepare("SELECT name, description, address, lat, lng, author, email, date FROM places WHERE place_id = ?");
        $stmt->bind_param("s", $placeId);
        if ($stmt->execute()) {
            $place = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $place;
        } else {
            return NULL;
        }
    }

}

?>