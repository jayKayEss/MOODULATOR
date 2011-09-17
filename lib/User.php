<?php

require_once 'LastFm.php';
require_once 'DB.php';

class User {

    private $lastfm_name;

    function __construct($lastfm_name) {
        $this->lastfm_name = $lastfm_name;
    }

    function getListeningHistory() {
        $lastfm = new LastFm();
        $db = new DB();



    }

}
