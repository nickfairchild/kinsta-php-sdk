<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Domains;

readonly class DNSRecordUpdate
{
    public function __construct(
        public string $type,
        public string $name,
        public int $ttl,
        public array $newResourceRecords,
        public array $removedResourceRecords,
    ) {}
}
