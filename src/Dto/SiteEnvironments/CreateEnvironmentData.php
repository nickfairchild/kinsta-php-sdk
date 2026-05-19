<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class CreateEnvironmentData
{
    public function __construct(
        public string $displayName,
        public string $envType,
    ) {}
}
