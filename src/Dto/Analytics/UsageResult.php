<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Analytics;

readonly class UsageResult
{
    public function __construct(
        public string $siteId,
        public int|float $value,
    ) {}

    public static function fromResponse(array $site, string $usageKey, string $metric): self
    {
        return new self(
            siteId: $site['id'],
            value: $site[$usageKey][$metric] ?? 0,
        );
    }
}
