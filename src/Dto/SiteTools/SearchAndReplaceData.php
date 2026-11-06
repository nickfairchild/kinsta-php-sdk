<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\SiteTools;

readonly class SearchAndReplaceData
{
    public function __construct(
        public string $search,
        public string $replace,
        public ?bool $performReplacement = null,
        public ?bool $isClearCache = null,
    ) {}
}
