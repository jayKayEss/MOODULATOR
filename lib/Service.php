<?php

abstract class Service {

    abstract function getConfig();

    abstract function fixParams(&$params);

    function callApi($uri, $params=null, $method=HTTP_METH_GET) {
        $config = $this->getConfig();

        $full_url = $config['baseurl'] . $uri;
        $request = new HttpRequest($full_url, $method);
        
        if ($params) {
            $this->fixParams($params);
            if ($method == HTTP_METH_GET) {
                $request->addQueryData($params);
            }
        }

        $response = $request->send();
        if ($request->getResponseCode() < 300) {
            $raw = $response->getBody();
            return json_decode($raw);
        }

    }

}
