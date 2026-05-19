<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Analytics;

readonly class AnalyticsResult
{
    public function __construct(
        public array $data,
    ) {}

    public static function fromResponse(array $response): self
    {
        return new self(
            data: $response['data'] ?? [],
        );
    }
}
