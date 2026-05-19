<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Analytics;

readonly class AnalyticsQuery
{
    public function __construct(
        public string $interval,
        public ?string $timezone = null,
    ) {}
}
