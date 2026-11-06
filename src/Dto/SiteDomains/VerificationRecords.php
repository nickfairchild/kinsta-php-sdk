<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteDomains;

readonly class VerificationRecords
{
    public function __construct(
        public array $records,
        public array $pointingRecords = [],
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            records: $data['site_domain']['verification_records'] ?? [],
            pointingRecords: $data['site_domain']['pointing_records'] ?? [],
        );
    }
}
