<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Analytics;

readonly class UsageResult
{
    public function __construct(
        public int $value,
        public string $unit,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            value: $data['value'] ?? 0,
            unit: $data['unit'] ?? '',
        );
    }
}
