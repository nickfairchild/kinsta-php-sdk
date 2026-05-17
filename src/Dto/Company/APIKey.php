<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class APIKey
{
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public ?string $expiresAt,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            status: $data['status'],
            expiresAt: $data['expires_at'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
