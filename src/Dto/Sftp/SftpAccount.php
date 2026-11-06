<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpAccount
{
    public function __construct(
        public string $id,
        public string $username,
        public string $rootDirectory,
        public bool $isActive,
        public string $permission,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            username: $data['username'],
            rootDirectory: $data['root_directory'],
            isActive: $data['is_active'] ?? false,
            permission: $data['permission'],
        );
    }
}
