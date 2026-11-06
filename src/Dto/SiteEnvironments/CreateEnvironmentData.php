<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class CreateEnvironmentData
{
    public function __construct(
        public string $displayName,
        public string $siteTitle,
        public bool $isPremium,
        public string $adminEmail,
        public string $adminPassword,
        public string $adminUser,
        public string $wpLanguage,
        public ?bool $isSubdomainMultisite = null,
        public ?bool $isMultisite = null,
    ) {}
}
