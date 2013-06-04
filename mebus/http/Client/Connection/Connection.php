<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Connection;

class Connection
{
    private $cache     = null;
    private $caching   = false;
    private $end_point = null;
    private $data      = array();

    /**
     * @var array
     */
    protected $http_error_codes = array(400, 404, 500);

    public function request($resource, $method = 'GET')
    {
        $uri = $this->getUri($resource);

        if($this->getCache() != null && $this->getCaching() != null)
        {
            $key = md5($uri);
            $response = $this->getCache()->get($key);
            if(empty($response))
            {
                $response = $this->execute($uri);
                $this->getCache()->set($key, $response);
            }
            return $response;
        }
        return $this->execute($uri, $method);
    }

    /**
     * @param $resource
     * @return string
     */
    public function getUri($resource)
    {
        $uri = "$this->end_point$resource";
        $get = $this->getQueryParam();
        if(!empty($get)) {
            $uri.= "?" . http_build_query($get);
        }
        return $uri;
    }

    /**
     * @param $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return null
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param $status
     */
    public function setCachig($status)
    {
        $this->caching = $status;
    }

    /**
     * @return bool
     */
    public function getCaching()
    {
        return $this->caching;
    }

    /**
     * @param $end_point
     * @return Client
     */
    public function setEndPoint($end_point)
    {
        $this->end_point = $end_point;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->end_point;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setQueryParam($key, $value = null)
    {
        return $this->setData($key, $value, 'GET');
    }

    /**
     * @param $key  | null
     * @return array|false
     */
    public function getQueryParam($key = null)
    {
        return $this->getData('GET', $key);
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setPostParam($key, $value = null)
    {
        return $this->setData($key, $value, 'POST');
    }

    /**
     * @param $key  |null
     * @return array|false
     */
    public function getPostParam($key = null)
    {
        return $this->getData('POST', $key);
    }

    public function setHeaderParam($key, $value = null)
    {
        return $this->setData($key, $value, 'HEADER');
    }

    public function getHeaderParam($key = null)
    {
        return $this->getData('HEADER', $key);
    }

    private function setData($key, $value, $type = 'GET')
    {
        $this->data[$type][$key] = $value;
        return $this;
    }

    private function getData($type, $key = null)
    {
        if(!empty($this->data[$type][$key])) {
            return $this->data[$type][$key];
        }
        return $this->data[$type]?:false;
    }
}