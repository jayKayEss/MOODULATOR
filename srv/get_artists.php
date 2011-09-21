<?php

require_once '../lib/LastFm.php';
require_once '../lib/Util.php';

$lastfm = new LastFm();

$user = $_GET['username'];
$page = $_GET['page'];

$data = $lastfm->callApi('/', array(
    'method' => 'user.getrecenttracks',
    'user' => $user,
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
        $artist_data = $lastfm->callApi('/', array(
            'method' => 'artist.getinfo',
            'mbid' => $artist_id
        ));

        $img_count = count($artist_data->artist->image);

        if ($img_count == 0) {
            $artist['image'] = 'img/unknown.png';
        } else {
            if ($img_count >= 3) {
                $image_data = (array)$artist_data->artist->image[2];
            } else {
                $image_data = (array)(end($artist_data->artist->image));
            }
        }

        $artist = (array)($artist_data->artist);
        $artist['image'] = $image_data['#text'];

        $ret[$season][$artist_id] = $artist;
    }

    if (!isset($ret[$season][$artist_id]['count']))
        $ret[$season][$artist_id]['count'] = 1;
    $ret[$season][$artist_id]['count']++;

}

header("Content-Type: application/json;charset=UTF-8");
print json_encode($ret);

