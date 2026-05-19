<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class LogsResult
{
    public function __construct(
        public array $logs,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            logs: $data['logs'] ?? [],
        );
    }
}
