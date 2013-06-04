<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Request;
use mebus\http\Client\Client;
use mebus\http\Client\Exception\InvalidMethodCallException;
use mebus\http\Client\Exception\NoResponseClassDefinedException;

abstract class Request
{
    /**
     * @var \Client|null
     */
    private $client;

    /**
     * @var string
     */
    protected $responseClass = null;

    private $method = 'GET';

    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->getClient()->execute($this);
    }

    /**
     * @param $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @param $args
     * @throws InvalidMethodCallException
     */
    public function __call($name, $args)
    {
        throw new InvalidMethodCallException('Function '. $name . '() not found!');
    }

    /**
     * @param $key
     * @param null $value
     * @return $this
     */
    public function setQueryParam($key, $value = null) {
        $this->getClient()->setQueryParam($key, $value);
        return $this;
    }

    /**
     * @param $key
     * @return array|false
     */
    public function getQueryParam($key = null)
    {
        return $this->getClient()->getQueryParam($key);
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setPostParam($key, $value = null)
    {
        $this->getClient()->setPostParam($key, $value);
        return $this;
    }

    /**
     * @param null $key
     * @return array|false
     */
    public function getPostParam($key = null)
    {
        return $this->getClient()->getPostParam($key);
    }

    public function setHeaderParam($key, $value = null)
    {
        $this->getClient()->setHeaderParam($key, $value);
        return $this;
    }

    public function getHeaderParam($key = null)
    {
        return $this->getClient()->getHeaderParam($key);
    }

    /**
     * @param $responseClass
     * @return $this
     */
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;
        return $this;
    }

    /**
     * @return mixed
     * @throws NoResponseClassDefinedException
     */
    public function getResponseObject()
    {
        if(empty($this->responseClass)) {
            throw new NoResponseClassDefinedException("no response class defined for $this->getName()");
        }
        return new $this->responseClass;
    }

    /**
     * @param $end_point
     * @return $this
     */
    public function setEndPoint($end_point)
    {
        $this->getClient()->setEndPoint($end_point);
        return $this;
    }

    public function getEndPoint()
    {
        return $this->getClient()->getEndPoint();
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }
}