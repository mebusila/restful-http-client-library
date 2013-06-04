<?php
/**
 * @author     Serban Carlogea
 */

namespace mebus\http\Client\Exception;
use mebus\http\Client\Exception\ApiException;

class ConnectionException extends ApiException
{
    /**
     * @param $uri
     * @param int $code
     * @param null $previous
     */
    public function __construct($uri, $code = 0, $previous = null)
    {
        parent::__construct('Invalid request: '.$uri, $code, $previous);
    }

}