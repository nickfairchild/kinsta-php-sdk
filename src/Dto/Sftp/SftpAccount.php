<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpAccount
{
    public function __construct(
        public string $username,
        public string $homeDirectory,
        public bool $isActive,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            username: $data['username'],
            homeDirectory: $data['home_directory'],
            isActive: $data['is_active'] ?? false,
        );
    }
}
