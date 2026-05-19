<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

readonly class FileListResult
{
    public function __construct(
        public string $environmentId,
        public array $files,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            environmentId: $data['environment_id'],
            files: $data['files'] ?? [],
        );
    }
}
