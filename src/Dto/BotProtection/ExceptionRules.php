<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class ExceptionRules
{
    public function __construct(
        public string $id,
        public array $exceptionRules,
        public bool $hasCustomWafRules,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            exceptionRules: ExceptionRule::collect($data['exception_rules']),
            hasCustomWafRules: $data['has_custom_waf_rules'],
        );
    }
}
