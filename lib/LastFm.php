<?php

require_once 'Service.php';

class LastFm extends Service {

    function getConfig() {
        return array(
            'key' => 'c6a5004e0c7371b8d8e1cf7d83d916bb',
            'secret' => 'db53ba293c9998f954c9ce040dd65a66',
            'baseurl' => 'http://ws.audioscrobbler.com/2.0'
        );
    }

    function fixParams(&$params) {
        $config = $this->getConfig();
        $params['api_key'] = $config['key'];
        $params['format'] = 'json';
    }

}


