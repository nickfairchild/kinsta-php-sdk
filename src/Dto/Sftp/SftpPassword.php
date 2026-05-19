<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpPassword
{
    public function __construct(
        public string $password,
        public ?int $expirationTime = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            password: $data['password'],
            expirationTime: $data['expiration_time'] ?? null,
        );
    }
}
