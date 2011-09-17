<?php

class DB {

    const DSN = "mysql:host=localhost;dbname=moodulator";
    const USER = 'moodulator';
    const PASS = 'absolute,quinine,discotheque';

    const getUser = "SELECT * FROM user WHERE lastfm_name=?";
    const addUser = "INSERT INTO user SET lastfm_name=?";

    private $dbh;

    function __construct() {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ); 
        $dbh = new PDO(self::DSN, self::USER, self::PASS, $options);
    }

    function getUser($lastfm_name) {
        $q = $this->dbh->prepare(self::getUser);


}
