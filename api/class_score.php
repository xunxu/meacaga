<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 27/10/2015
 * Time: 13:52
 */
class Score {
    var $scoreId, $placeId, $paper, $size, $waitTime, $cleanliness, $smell;

    function __construct($scoreId, $placeId, $paper, $size, $waitTime, $cleanliness, $smell){
        $this->scoreId=$scoreId;
        $this->placeId=$placeId;
        $this->paper=$paper;
        $this->size=$size;
        $this->waitTime=$waitTime;
        $this->cleanliness=$cleanliness;
        $this->smell=$smell;
    }
}

?>