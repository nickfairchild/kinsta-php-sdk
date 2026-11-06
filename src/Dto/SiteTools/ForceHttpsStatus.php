<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

readonly class ForceHttpsStatus
{
    public function __construct(
        public string $status,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            status: $data['force_https_status'],
        );
    }
}
