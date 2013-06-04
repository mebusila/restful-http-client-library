<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Cache;

interface InterfaceCache
{
    /**
     * @abstract
     * @param $key
     * @param $data
     */
    public function set($key, $data);

    /**
     * @abstract
     * @param $key
     */
    public function get($key);

    /**
     * @abstract
     * @param $key
     */
    public function delete($key);
}