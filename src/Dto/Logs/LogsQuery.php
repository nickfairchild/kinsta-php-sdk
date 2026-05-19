<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class LogsQuery
{
    public function __construct(
        public string $logType,
    ) {}
}
