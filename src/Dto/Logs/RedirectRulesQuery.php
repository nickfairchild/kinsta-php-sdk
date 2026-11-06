<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Logs;

readonly class RedirectRulesQuery
{
    public function __construct(
        public ?int $limit = null,
        public ?int $offset = null,
        public ?string $key = null,
        public ?string $order = null,
        public ?string $searchQuery = null,
        public ?bool $regexSearch = null,
    ) {}
}
