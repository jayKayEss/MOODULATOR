<?php

require_once '../lib/LastFm.php';
require_once '../lib/Util.php';

$lastfm = new LastFm();

$mbid = $_GET['mbid'];

$data = $lastfm->callApiRaw('/', array(
    'method' => 'artist.getinfo',
    'mbid' => $mbid
));

header("Content-Type: application/json;charset=UTF-8");
print $data;

