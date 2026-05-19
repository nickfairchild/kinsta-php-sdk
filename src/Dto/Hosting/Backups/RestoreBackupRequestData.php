<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class RestoreBackupRequestData
{
    public function __construct(
        public int $backupId,
        public string $notifiedUserId,
    ) {}
}
