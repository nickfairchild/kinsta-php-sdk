<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteDomains;

readonly class VerificationRecords
{
    public function __construct(
        public array $records,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            records: $data['verification_records'] ?? [],
        );
    }
}
