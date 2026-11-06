<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Analytics;

readonly class AnalyticsQuery
{
    /**
     * @param  string|null  $timeSpan  One of 24_hours, 7_days, 30_days, 60_days (diskspace: no 24_hours).
     * @param  string|null  $timeZone  UTC offset such as "00:00" or "-05:00"; only used by the disk space endpoint.
     */
    public function __construct(
        public ?string $timeSpan = null,
        public ?string $from = null,
        public ?string $to = null,
        public ?string $timeZone = null,
    ) {}
}
