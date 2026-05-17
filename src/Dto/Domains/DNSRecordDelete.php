<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Domains;

readonly class DNSRecordDelete
{
    public function __construct(
        public string $type,
        public string $name,
    ) {}
}
