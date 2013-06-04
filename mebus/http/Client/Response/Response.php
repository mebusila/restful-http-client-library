<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Response;
use mebus\http\Client\Exception\InvalidResponseException;

class Response
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var
     */
    protected $rawData;

    /**
     * @var array
     */
    protected $items;

    /**
     * @param $data
     */
    public function __construct($data = null)
    {
        $this->setName(get_class($this));
        if(!empty($data)) {
            $this->setJSONResult($data);
        }
    }

    public function setJSONResult($response)
    {
        $data = $response['data'];
        $code = $response['code'];

        if(empty($data)) {
            throw new InvalidResponseException(
                sprintf(
                    'Invalid %s',
                    $this->getName()
                )
            );
        }
        $data = json_decode($data, true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                throw new InvalidResponseException(
                    sprintf(
                        '%s returned "JSON_ERROR_DEPTH - Maximum stack depth exceeded"',
                        $this->getName()
                    )
                );
                break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new InvalidResponseException(
                    sprintf(
                        '%s returned "JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch"',
                        $this->getName()
                    )
                );
                break;
            case JSON_ERROR_CTRL_CHAR:
                throw new InvalidResponseException(
                    sprintf(
                        '%s returned "JSON_ERROR_CTRL_CHAR - Unexpected control character found"',
                        $this->getName()
                    )
                );
                break;
            case JSON_ERROR_SYNTAX:
                throw new InvalidResponseException(
                    sprintf(
                        '%s returned "JSON_ERROR_SYNTAX - Syntax error, malformed JSON"',
                        $this->getName()
                    )
                );
                break;
        }
        $this->setRawData($response);
        $this->parseData($data, $code);
    }

    /**
     * @param $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $data
     */
    public function setRawData($data)
    {
        $this->rawData = $data;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * @param null $idx
     * @return array
     */
    public function getItems($idx = null)
    {
        if(is_null($idx)) {
            return $this->items;
        }
        return $this->items[$idx];
    }

    /**
     * @param $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
}