<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

readonly class SitePayload
{
    public function __construct(
        public string $displayName,
        public string $region,
        public string $adminEmail,
        public string $adminPassword,
        public string $adminUser,
        public string $siteTitle,
        public string $wpLanguage,
        public string $company,
        public bool $isSubdomainMultisite = false,
        public bool $isMultisite = false,
        public bool $woocommerce = false,
        public bool $wordpressSeo = false,
    ) {}
}
