<?php

require_once 'Service.php';

class LastFm extends Service {

    private $mc;

    const TTL = 3600;

    function __construct() {
        $this->mc = new Memcached();
        $this->mc->addServer('127.0.0.1', 11211);
    }

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

    function callApi($uri, $params=null, $method=HTTP_METH_GET) {
        $key = $method.':'.$uri.':'.json_encode($params);
        $data = $this->mc->get($key);
        if ($data) {
            error_log("CACHE HIT: $key");
            error_log(print_r($data, true));
        } else {
            $data = parent::callApi($uri, $params, $method);
            $this->mc->set($key, $data, self::TTL);
        }
    
        return $data;        
    }
}


