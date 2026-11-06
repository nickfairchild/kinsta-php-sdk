<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class RedirectRulesUpdateData
{
    public function __construct(
        public string $actionType,
        public ?array $rulesToUpdate = null,
        public ?array $newValue = null,
    ) {}
}
