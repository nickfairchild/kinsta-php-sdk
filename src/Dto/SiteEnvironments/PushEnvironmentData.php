<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments;

readonly class PushEnvironmentData
{
    public function __construct(
        public string $sourceEnvId,
        public string $targetEnvId,
        public ?bool $pushDb = null,
        public ?bool $pushFiles = null,
        public ?bool $runSearchAndReplace = null,
        public ?string $pushFilesOption = null,
        public ?array $fileList = null,
    ) {}
}
