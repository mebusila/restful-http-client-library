<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Connection;
use mebus\http\Client\Connection\Connection;
use mebus\http\Client\Connection\InterfaceConnection;

class Curl extends Connection implements InterfaceConnection
{
    /**
     * @var string
     */
    private $uri;

    public function execute($uri, $method = 'GET')
    {
        $this->uri = $uri;
        $ch = curl_init($this->uri);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        }

        $post_params = $this->getPostParam();
        if(!empty($post_params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        }

        //TODO: add header params to curl (values can be found with $this->getHeaderParam())

        $response = array(
            'data' => curl_exec($ch),
            'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        );

        curl_close($ch);
        return $response;
    }
}