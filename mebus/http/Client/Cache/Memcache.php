<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Cache;
use mebus\http\Client\Cache\Cache;
use mebus\http\Client\Cache\InterfaceCache;

class Memcache extends Cache implements InterfaceCache
{
    /**
     * @var
     */
    private $memcache;

    /**
     * maximum size for one chunk stored in memcache
     * @var int
     */
    protected $maxChunkSize = 1000000;

    /**
     * @param Memcache $memcache
     */
    public function __construct(Memcache $memcache)
    {
        $this->setMemcache($memcache);
    }

    /**
     * @param $key
     * @param $data
     * @return bool
     */
    public function set($key, $data)
    {
        $result = $this->memcache->set($key, $data, MEMCACHE_COMPRESSED, (int) $this->getTtl());
        if($result === true) {
            return true;
        }
        return $this->setChunks($key, $data);

    }

    /**
     * @param $key
     * @return null|string
     */
    public function get($key)
    {
        $data = $this->memcache->get($key);
        if(is_array($data) && !empty($data['chunk_keys'])) {
            $data = $this->getChunks($key, $data);
        }

        return $data;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     * @param $memcache
     */
    public function setMemcache($memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * @return mixed
     */
    public function getMemcache()
    {
        return $this->memcache;
    }
}