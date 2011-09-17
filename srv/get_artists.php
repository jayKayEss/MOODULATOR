<?php

require_once '../lib/LastFm.php';
require_once '../lib/Util.php';

$lastfm = new LastFm();

$user = $_GET['lastfm_name'];
$page = $_GET['page'];

$data = $lastfm->callApi('/', array(
    'method' => 'user.getrecenttracks',
    'user' => 'jaykayess',
    'page' => $page,
    'limit' => 25
));

$ret = array();

foreach ($data->recenttracks->track as $track) {
    if (isset($track->date)) {
        $date = $track->date->uts;
    } else { // nowplaying
        $date = time();
    }

    error_log("DATE: $date");

    $season = Util::getSeason($date);

    if (!isset($ret[$season])) $ret[$season] = array();

    $artist_id = $track->artist->mbid;

    if (!isset($ret[$season][$artist_id])) {
        $artist = (array)$track->artist;
        $artist['name'] = $artist['#text'];
        unset($artist['#text']);
        $image = (array)(end($track->image));
        $artist['image'] = $image['#text'];
        $ret[$season][$artist_id] = $artist;
    }

    if (!isset($ret[$season][$artist_id]['count']))
        $ret[$season][$artist_id]['count'] = 1;
    $ret[$season][$artist_id]['count']++;

}

header("Content-Type: application/json;charset=UTF-8");
print json_encode($ret);

