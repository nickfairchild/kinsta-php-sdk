<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Domains;

readonly class Domain
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $siteId,
        public bool $isActive,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            siteId: $data['site_id'] ?? '',
            isActive: $data['is_active'] ?? false,
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
