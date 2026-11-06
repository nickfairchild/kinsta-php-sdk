<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

use Nickfairchild\KinstaPhpSdk\Enums\ForceHttpsType;

readonly class ForceHttpsStatus
{
    public function __construct(
        public ?ForceHttpsType $status,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            // Values outside the documented enum become null rather than throwing.
            status: ForceHttpsType::tryFrom($data['force_https_status'] ?? ''),
        );
    }
}
