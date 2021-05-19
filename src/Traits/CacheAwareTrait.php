<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Psr\SimpleCache\CacheInterface;

trait CacheAwareTrait
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    private function getCache(): CacheInterface
    {
        if (!isset($this->cache)) {
            $this->cache = new ArrayCachePool();
        }
        return $this->cache;
    }
}
