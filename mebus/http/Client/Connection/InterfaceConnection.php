<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Connection;

interface InterfaceConnection
{
    /**
     * @param $uri
     * @param string $method
     * @return mixed
     */
    public function execute($uri, $method = 'GET');
}