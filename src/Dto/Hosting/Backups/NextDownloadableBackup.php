<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class NextDownloadableBackup
{
    public function __construct(
        public bool $isBackupAvailable,
        public ?string $resetAt,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            isBackupAvailable: $data['is_backup_available'],
            resetAt: $data['reset_at'] ?? null,
        );
    }
}
