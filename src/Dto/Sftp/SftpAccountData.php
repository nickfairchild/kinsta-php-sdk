<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpAccountData
{
    public function __construct(
        public string $username,
        public string $password,
    ) {}
}
