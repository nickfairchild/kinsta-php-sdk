<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class LogsResult
{
    public function __construct(
        public string $logs,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            logs: $data['environment']['container_info']['logs'] ?? '',
        );
    }
}
