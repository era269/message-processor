<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Era269\MethodMap\MethodMapInterface;

interface ApplyEventMethodMapAwareInterface
{
    public function setApplyEventMethodMap(MethodMapInterface $applyEventMethodMap): void;
}
