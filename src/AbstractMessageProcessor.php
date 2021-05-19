<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Era269\MessageProcessor\Traits\CacheAwareTrait;
use Era269\MessageProcessor\Traits\CanApplyPrivateEventsTrait;
use Era269\MessageProcessor\Traits\CanProcessMessage;

abstract class AbstractMessageProcessor implements MessageProcessorInterface
{
    use CacheAwareTrait;
    use CanProcessMessage;
    use CanApplyPrivateEventsTrait;
}
