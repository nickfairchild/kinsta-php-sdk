<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

readonly class PhpAllocationData
{
    public function __construct(
        public int $threadCount,
        public int $threadMemory,
    ) {}
}
