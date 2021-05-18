# message-processor
![PHP Stan Badge](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat">)
[![codecov](https://codecov.io/gh/era269/message-processor/branch/main/graph/badge.svg?token=OPBJWPKD6S)](https://codecov.io/gh/era269/message-processor)

Functionality of automated method choosing for message processing

## How To Use
extend \Era269\MessageProcessor\AbstractMessageProcessor
`or` use \Era269\MessageProcessor\Traits\CanGetMethodNameByMessageTrait + implement method process
