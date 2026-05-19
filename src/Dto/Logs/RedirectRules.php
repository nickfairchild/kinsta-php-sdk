<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class RedirectRules
{
    public function __construct(
        public array $rules,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            rules: $data['redirect_rules'] ?? [],
        );
    }
}
