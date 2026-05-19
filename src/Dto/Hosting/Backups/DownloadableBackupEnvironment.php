<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class DownloadableBackupEnvironment
{
    public function __construct(
        public string $displayName,
        public array $downloadableBackups,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            displayName: $data['display_name'],
            downloadableBackups: DownloadableBackup::collect($data['downloadable_backups']),
        );
    }
}
