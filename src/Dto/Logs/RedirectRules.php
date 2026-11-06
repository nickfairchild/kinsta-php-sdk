<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class RedirectRules
{
    public function __construct(
        public array $rules,
        public ?int $count = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            rules: $data['environment']['container_info']['redirect_rules']['rules'] ?? [],
            count: $data['environment']['container_info']['redirect_rules']['redirect_rules_count'] ?? null,
        );
    }
}
