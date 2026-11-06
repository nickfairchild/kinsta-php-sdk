<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteDomains;

readonly class SiteDomains
{
    public function __construct(
        public array $domains,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            domains: $data['environment']['site_domains'] ?? [],
        );
    }
}
