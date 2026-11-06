<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class SiteCapabilityData
{
    public function __construct(
        public string $siteId,
        public string $role,
    ) {}
}
