<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Cache;

class Cache
{
    /**
     * @var int
     */
    protected $ttl = 3600;

    /**
     * maximum size for one chunk stored in cache
     * @var int
     */
    protected $maxChunkSize = 1000000;

    /**
     * @param $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * stores the given data as chunks
     *
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    protected function setChunks($key, $data)
    {
        $data = json_encode($data);
        if(strlen($data) < $this->maxChunkSize) {
            return $this->set($key, $data);
        }

        $data = str_split($data, $this->maxChunkSize);
        foreach($data as $chunk)
        {
            $chunkKey = md5($chunk);
            $info['chunk_keys'][] = $chunkKey;
            $this->set($chunkKey, $chunk);
        }
        return $this->set($key, $info);
    }

    /**
     *
     * @param string $key
     * @param array $info
     * @return mixed
     */
    protected function getChunks($key, $info)
    {
        $chunkData = '';
        foreach($info['chunk_keys'] as $chunkKey) {
            $data =  $this->get($chunkKey);
            if($data == null) {
                $this->deleteRelatedChunks($key, $info['chunk_keys']);
                return null;
            }
            $chunkData .= $data;
        }
        return json_decode($chunkData);
    }

    /**
     *
     * @param string $key
     * @param array $chunkKeys
     * @return bool
     */
    private function deleteRelatedChunks($key, $chunkKeys)
    {
        if(!empty($chunkKeys)) {
            foreach ($chunkKeys as $chunkKey) {
                $this->delete($chunkKey);
            }
        }
        return $this->delete($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function checkValidCache($key)
    {
        return (bool) $this->get($key);
    }
}