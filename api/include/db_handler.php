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
        require_once dirname(__FILE__) . '/db_connect.php';
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
     * @param String $place_id place ID
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
     * Get last added places
     * @return place list
     */
    public function getLastAddedPlaces() {
        $place_id = null; $name = null;

        $stmt = $this->conn->prepare("SELECT place_id, name FROM places ORDER BY date DESC LIMIT 5");
        if($stmt->execute()){
            $stmt->bind_result($place_id, $name);

            $result = array();
            $num_places = 0;
            while ($stmt->fetch()) {
                $result[] = array(
                    'place_id' => $place_id,
                    'name' => $name,
                );
                $num_places++;
            }

            if(!$num_places == 0){
                $stmt->close();
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

    /**
     * Get the number of places
     * @return number of places
     */
    public function getNumberOfPlaces() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM places");
        if ($stmt->execute()) {
            $places = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $places;
        } else {
            return NULL;
        }
    }

    /**
     * Get most visited places
     * @return place list
     */
    public function getMostVisitedPlaces() {
        $place_id = null; $name = null; $visited=null;

        $stmt = $this->conn->prepare("SELECT places.place_id, places.name, COUNT(*) as visited FROM places
                                      LEFT JOIN comments on comments.place_id = places.place_id
                                      LEFT JOIN scores on scores.place_id = places.place_id
                                      GROUP BY places.place_id
                                      ORDER BY visited DESC
                                      LIMIT 5");
        if($stmt->execute()){
            $stmt->bind_result($place_id, $name, $visited);

            $result = array();
            $num_places = 0;
            while ($stmt->fetch()) {
                $result[] = array(
                    'place_id' => $place_id,
                    'name' => $name,
                    'visited' => $visited,
                );
                $num_places++;
            }

            if(!$num_places == 0){
                $stmt->close();
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


    /**
     * Get top rated places
     * @return place list
     */
    public function getTopRatedPlaces() {
        $place_id = null; $name = null; $score = null;

        $stmt = $this->conn->prepare("SELECT t2.place_id, places.name, ((`total_score2`/ `num_scores`)/5) as `score` FROM
                                    (SELECT place_id, COUNT(*) AS `num_scores`, SUM(`total_score1`) as `total_score2` FROM
                                    (SELECT place_id, (paper+cleanliness+size+smell+wait_time) as `total_score1` FROM `scores`) t1
                                    GROUP BY place_id) t2
                                    LEFT JOIN places ON places.place_id = t2.place_id
                                    ORDER BY `score` DESC LIMIT 5");
        if($stmt->execute()){
            $stmt->bind_result($place_id, $name, $score);

            $result = array();
            $num_places = 0;
            while ($stmt->fetch()) {
                $result[] = array(
                    'place_id' => $place_id,
                    'name' => $name,
                    'score' => round($score * 2)/2,
                );
                $num_places++;
            }

            if(!$num_places == 0){
                $stmt->close();
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

    /* ------------- `scores` table method ------------------ */

    /**
     * Add new score
     * @param $place_id
     * @param $paper
     * @param $size
     * @param $wait_time
     * @param $cleanliness
     * @param $smell
     */
    public function addScore($place_id, $paper, $size, $wait_time, $cleanliness, $smell) {
        // insert query
        $stmt = $this->conn->prepare("INSERT INTO scores(place_id, paper, size, wait_time, cleanliness, smell) values(?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $place_id, $paper, $size, $wait_time, $cleanliness, $smell);
        $result = $stmt->execute();
        if($result){
            $result = $stmt->insert_id;
        }
        $stmt->close();
        return $result;
    }

    /**
     * Get score by place ID
     * @param String $place_id place ID
     * @return score
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

    /* ------------- `comments` table method ------------------ */

    /**
     * Get comments by place ID
     * @param String $place_id place ID
     * @return comments
     */
    public function getCommentsByPlaceId($place_id) {
        $comment = null; $date = null;

        $stmt = $this->conn->prepare("SELECT comment, date FROM comments WHERE place_id = ?");
        $stmt->bind_param("s", $place_id);
        if($stmt->execute()){
            $stmt->bind_result($comment, $date);

            $result = array();
            $num_comments = 0;
            while ($stmt->fetch()) {
                $result[] = array(
                    'comment' => $comment,
                    'date' => $date,
                );
                $num_comments++;
            }

            if(!$num_comments == 0){
                $stmt->close();
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

    /**
     * Add new comment
     * @param $place_id
     * @param $comment
     */
    public function addComment($place_id, $comment) {
        // insert query
        $stmt = $this->conn->prepare("INSERT INTO comments(place_id, comment) values(?,?)");
        $stmt->bind_param("ss", $place_id, $comment);
        $result = $stmt->execute();
        if($result){
            $result = $stmt->insert_id;
        }
        $stmt->close();
        return $result;
    }

    /* ------------- `photos` table method ------------------ */

    /**
     * Add new photo
     * @param $place_id
     * @param $name
     */
    public function addPhoto($place_id, $name) {
        // insert query
        $stmt = $this->conn->prepare("INSERT INTO photos(place_id, name) values(?,?)");
        $stmt->bind_param("ss", $place_id, $name);
        $result = $stmt->execute();
        if($result){
            $result = $stmt->insert_id;
        }
        $stmt->close();
        return $result;
    }

    /**
     * Get photos by place ID
     * @param String $place_id place ID
     * @return photos
     */
    public function getPhotosByPlaceId($place_id) {
        $name = null;

        $stmt = $this->conn->prepare("SELECT name FROM photos WHERE place_id = ?");
        $stmt->bind_param("s", $place_id);
        if($stmt->execute()){
            $stmt->bind_result($name);

            $result = array();
            $num_photos = 0;
            while ($stmt->fetch()) {
                $result[] = array(
                    'photo' => $name,
                );
                $num_photos++;
            }

            if(!$num_photos == 0){
                $stmt->close();
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