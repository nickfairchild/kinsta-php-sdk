<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpPassword
{
    public function __construct(
        public string $password,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            password: $data['sftp_password'],
        );
    }
}
