<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Sftp;

readonly class SftpAccounts
{
    public function __construct(
        public array $accounts,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            accounts: array_map(
                fn (array $acc) => SftpAccount::fromResponse($acc),
                $data['additional_sftp_accounts'] ?? [],
            ),
        );
    }
}
