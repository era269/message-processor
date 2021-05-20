<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MethodMap\MethodMapInterface;

trait ApplyEventMethodMapAwareTrait
{
    /**
     * @var MethodMapInterface
     */
    private $applyEventMethodMap;

    protected function getApplyEventMethodMap(): MethodMapInterface
    {
        if (!isset($this->applyEventMethodMap)) {
            throw new ParameterIsNotSetLogicException(static::class, 'applyEventMethodMap');
        }

        return $this->applyEventMethodMap;
    }

    public function setApplyEventMethodMap(MethodMapInterface $applyEventMethodMap): void
    {
        $this->applyEventMethodMap = $applyEventMethodMap;
    }
}
