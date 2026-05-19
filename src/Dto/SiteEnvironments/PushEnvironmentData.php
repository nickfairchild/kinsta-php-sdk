<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class PushEnvironmentData
{
    public function __construct(
        public string $sourceEnvId,
        public string $targetEnvId,
    ) {}
}
