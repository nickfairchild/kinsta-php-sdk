<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteDomains;

readonly class AddSiteDomainData
{
    public function __construct(
        public string $domain,
        public ?bool $isPrimary = null,
    ) {}
}
