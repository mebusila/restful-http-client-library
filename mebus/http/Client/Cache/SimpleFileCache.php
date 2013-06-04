<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Cache;
use mebus\http\Client\Cache\Cache;
use mebus\http\Client\Cache\InterfaceCache;

class SimpleFileCache extends Cache implements InterfaceCache
{
    /**
     * @var string
     */
    private $caching_folder;

    /**
     * @var string
     */
    private $file_prefix = 'Simplesurance_Api_Cache_';

    /**
     * @var int
     */
    protected $ttl = 3600;

    public function __construct($cachig_folder = null)
    {
        $this->setCachingFolder(sys_get_temp_dir() . DIRECTORY_SEPARATOR);
        if(!empty($cachig_folder))
            $this->setCachingFolder($cachig_folder);
    }

    /**
     * @param $key
     * @param $data
     * @return bool
     */
    public function set($key, $data)
    {
        $file = fopen($this->getCachingFile($key), 'w');
        fwrite($file, $data);
        fclose($file);
        return true;
    }

    /**
     * @param $key
     * @return null|string
     */
    public function get($key)
    {
        if($this->checkValidCache($key))
            $data = file_get_contents($this->getCachingFile($key));
        else
            return null;
        return $data;
    }

    /**
     * @param $key
     * @return bool
     */
    public function checkValidCache($key)
    {
        if(!file_exists($this->getCachingFile($key)))
            return false;
        if((time() - filemtime($this->getCachingFile($key))) > $this->getTtl())
        {
            $this->delete($key);
            return false;
        }
        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return unlink($this->getCachingFile($key));
    }

    /**
     * @param $key
     * @return string
     */
    public function getCachingFile($key)
    {
        return $this->getCachingFolder() . $this->getFileCachePrefix() . $key;
    }

    /**
     * @param $prefix string
     */
    public function setFileCachePrefix($prefix)
    {
        $this->file_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getFileCachePrefix()
    {
        return $this->file_prefix;
    }

    /**
     * @param $folder string
     */
    public function setCachingFolder($folder)
    {
        $this->caching_folder = $folder;
    }

    /**
     * @return string
     */
    public function getCachingFolder()
    {
        return $this->caching_folder;
    }

}