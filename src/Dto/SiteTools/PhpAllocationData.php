<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

readonly class PhpAllocationData
{
    public function __construct(
        public string $memory,
        public string $maxExecutionTime,
        public string $httpMaxConnections,
    ) {}
}
