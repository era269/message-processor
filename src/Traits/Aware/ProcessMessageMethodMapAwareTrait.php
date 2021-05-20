<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MethodMap\MethodMapInterface;

trait ProcessMessageMethodMapAwareTrait
{
    /**
     * @var MethodMapInterface
     */
    private $processMessageMethodMap;

    protected function getProcessMessageMethodMap(): MethodMapInterface
    {
        if (!isset($this->processMessageMethodMap)) {
            throw new ParameterIsNotSetLogicException(static::class, 'processMessageMethodMap');
        }

        return $this->processMessageMethodMap;
    }

    public function setProcessMessageMethodMap(MethodMapInterface $processMessageMethodMap): void
    {
        $this->processMessageMethodMap = $processMessageMethodMap;
    }
}
