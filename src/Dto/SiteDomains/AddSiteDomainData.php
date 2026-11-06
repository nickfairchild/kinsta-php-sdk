<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteDomains;

readonly class AddSiteDomainData
{
    public function __construct(
        public string $domain,
        public ?bool $isWildcardless = null,
        public ?bool $addWithWwwSubdomain = null,
        public ?string $setupType = null,
        public ?string $customSslKey = null,
        public ?string $customSslCert = null,
    ) {}
}
