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
     */
    public function addPlace($name, $description, $address, $lat, $lng, $author, $email) {
        // insert query
        $stmt = $this->conn->prepare("INSERT INTO places(name, description, address, lat, lng, author, email) values(?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $name, $description, $address, $lat, $lng, $author, $email);
        $result = $stmt->execute();
        if($result){
            $result = $stmt->insert_id;
        }
        $stmt->close();
        return $result;
    }


    /**
     * Get place by place ID
     * @param String $placeId place ID
     * @return place
     */
    public function getPlaceById($place_id) {
        $stmt = $this->conn->prepare("SELECT name, description, address, lat, lng, author, email, date FROM places WHERE place_id = ?");
        $stmt->bind_param("s", $place_id);
        if ($stmt->execute()) {
            $place = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $place;
        } else {
            return NULL;
        }
    }

    /**
     * Get score by place ID
     * @param String $placeId place ID
     * @return place
     */
    public function getScoreByPlaceId($place_id) {
        $paper = null; $size = null; $wait_time = 0; $cleanliness = 0; $smell = 0;
        $total_paper = null; $total_size = 0; $total_wait_time = 0; $total_cleanliness = 0; $total_smell = 0;

        $stmt = $this->conn->prepare("SELECT paper, size, wait_time, cleanliness, smell FROM scores WHERE place_id = ?");
        $stmt->bind_param("s", $place_id);
        if($stmt->execute()){
            $stmt->bind_result($paper, $size, $wait_time, $cleanliness, $smell);

            $num_scores = 0;
            while ($stmt->fetch()) {
                $total_paper += $paper;
                $total_size += $size;
                $total_wait_time += $wait_time;
                $total_cleanliness += $cleanliness;
                $total_smell += $smell;
                $num_scores++;
            }

            if(!$num_scores == 0){
                $stmt->close();
                $paper = round(($total_paper / $num_scores) * 2) / 2;
                $size = round(($total_size / $num_scores) * 2) / 2;
                $wait_time = round(($total_wait_time / $num_scores) * 2) / 2;
                $cleanliness = round(($total_cleanliness / $num_scores) * 2) / 2;
                $smell = round(($total_smell / $num_scores) * 2) / 2;
                $result = array('paper' => $paper, 'size' => $size, 'wait_time' => $wait_time, 'cleanliness' => $cleanliness, 'smell' => $smell);
                return $result;
            }
            else{
                $stmt->close();
                return NULL;
            }
        }
        else{
            $stmt->close();
            return NULL;
        }
    }
}

?>