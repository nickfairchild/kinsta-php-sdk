<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Domains;

readonly class DNSRecord
{
    public function __construct(
        public string $type,
        public string $name,
        public int $ttl,
        public array $resourceRecords,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            type: $data['type'],
            name: $data['name'],
            ttl: $data['ttl'],
            resourceRecords: $data['resource_records'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
