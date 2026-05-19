<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class AddManualBackupRequestData
{
    public function __construct(
        public string $tag = '',
    ) {}
}
