<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpConfig
{
    public function __construct(
        public string $host,
        public string $port,
        public string $username,
        public ?string $sshCommand = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            host: $data['host'],
            port: $data['port'],
            username: $data['user'],
            sshCommand: $data['ssh_command'] ?? null,
        );
    }
}
