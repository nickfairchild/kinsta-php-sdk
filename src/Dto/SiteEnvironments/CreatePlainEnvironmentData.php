<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class CreatePlainEnvironmentData
{
    public function __construct(
        public string $displayName,
        public bool $isPremium = false,
    ) {}
}
