<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client;

use mebus\http\Client\Connection\Connection;
use mebus\http\Client\Connection\Curl;
use mebus\http\Client\Request\Request;

class Client
{
    /**
     * @var \Connection
     */
    private $connection;

    public function __construct($end_point = null, Connection $connection = null)
    {
        $this->setConnection($connection ?: new Curl());
        if(!is_null($end_point)) {
            $this->setEndPoint($end_point);
        }
        return $this;
    }

    public function execute(Request $request)
    {
        $result = $this->getConnection()->request($request->getResource(), $request->getMethod());
        $response = $request->getResponseObject();
        $response->setJSONResult($result);
        return $response;
    }

    /**
     * @param $key
     * @param null $value
     * @return $this
     */
    public function setQueryParam($key, $value = null)
    {
        $this->getConnection()->setQueryParam($key, $value);
        return $this;
    }

    /**
     * @param null $key
     * @return array|false
     */
    public function getQueryParam($key = null)
    {
        return $this->getConnection()->getQueryParam($key);
    }

    public function setPostParam($key, $value = null)
    {
        $this->getConnection()->setPostParam($key, $value);
        return $this;
    }

    public function getPostParam($key = null)
    {
        return $this->getConnection()->getPostParam($key);
    }

    public function setHeaderParam($key, $value = null)
    {
        $this->getConnection()->setHeaderParam($key, $value);
        return $this;
    }

    public function getHeaderParam($key = null)
    {
        return $this->getConnection()->getHeaderParam($key);
    }

    /**
     * @param $connection
     * @return Client
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param $end_point
     * @return Client
     */
    public function setEndPoint($end_point)
    {
        $this->getConnection()->setEndPoint($end_point);
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->getConnection()->getEndPoint();
    }

    /**
     * @param $status
     * @return Client
     */
    public function setDisableCaching($status)
    {
        $this->getConnection()->setCachig(!$status);
        return $this;
    }

    /**
     * @return bool
     */
    public function getDisableCaching()
    {
        return !$this->getConnection()->getCaching();
    }

    /**
     * @param $cache
     * @return Client
     */
    public function setCache($cache)
    {
        $this->getConnection()->setCache($cache);
        return $this;
    }

    /**
     * @return null
     */
    public function getCache()
    {
        return $this->getConnection()->getCache();
    }
}