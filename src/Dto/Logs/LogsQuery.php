<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class LogsQuery
{
    public function __construct(
        public string $fileName = 'error',
        public int $lines = 1000,
        public ?string $from = null,
        public ?string $to = null,
        public ?string $search = null,
    ) {}
}
