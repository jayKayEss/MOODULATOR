<?php

//require 'lib/LastFm.php';

//$lastfm = new LastFm();
//$data = $lastfm->callApi('/', array(
//    'method' => 'user.getrecenttracks',
//    'user' => 'jaykayess'
//)); 


//var_dump($data);

//http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=rj&api_key=b25b959554ed76058ac220b7b2e0a026

require 'lib/User.php';

$user = new User('jaykayess');
$user->getListeningHistory();


