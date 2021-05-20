<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
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

    protected function getCache(): CacheInterface
    {
        if (!isset($this->cache)) {
            throw new ParameterIsNotSetLogicException(static::class, 'cache');
        }
        return $this->cache;
    }
}
