<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpStatus
{
    public function __construct(
        public bool $isSshEnabled,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            isSshEnabled: $data['is_ssh_enabled'] ?? false,
        );
    }
}
