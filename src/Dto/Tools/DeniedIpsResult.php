<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Tools;

readonly class DeniedIpsResult
{
    public function __construct(
        public array $ips,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            ips: $data['environment']['ip_list'] ?? [],
        );
    }
}
