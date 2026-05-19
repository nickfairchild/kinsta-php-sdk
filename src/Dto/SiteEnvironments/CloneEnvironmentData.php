<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class CloneEnvironmentData
{
    public function __construct(
        public string $displayName,
        public string $sourceEnvId,
    ) {}
}
