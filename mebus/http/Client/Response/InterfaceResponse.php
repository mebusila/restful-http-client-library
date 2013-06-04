<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Response;

interface InterfaceResponse
{
    /**
     * @param $data
     * @param $code
     * @return mixed
     */
    function parseData($data, $code = null);
}