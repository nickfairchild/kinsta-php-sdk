<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpStatus
{
    public function __construct(
        public bool $sshEnabled,
        public bool $sftpEnabled,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            sshEnabled: $data['ssh_enabled'] ?? false,
            sftpEnabled: $data['sftp_enabled'] ?? false,
        );
    }
}
