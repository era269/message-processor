<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Psr\SimpleCache\CacheInterface;

interface CacheAwareInterface
{
    public function setCache(CacheInterface $cache): void;
}
