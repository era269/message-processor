<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Era269\MessageProcessor\Traits\CanProcessMessageTrait;

abstract class AbstractMessageProcessor implements MessageProcessorInterface
{
    use CanProcessMessageTrait;
}
