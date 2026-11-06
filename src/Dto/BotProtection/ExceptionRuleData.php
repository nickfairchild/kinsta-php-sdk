<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class ExceptionRuleData
{
    public function __construct(
        public string $type,
        public string $action,
        public string $value,
    ) {}
}
