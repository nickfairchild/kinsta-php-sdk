<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class BackupEnvironment
{
    public function __construct(
        public string $displayName,
        public array $backups,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            displayName: $data['display_name'],
            backups: Backup::collect($data['backups']),
        );
    }
}
