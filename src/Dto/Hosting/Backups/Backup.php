<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class Backup
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $note,
        public string $type,
        public int $createdAt,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            note: $data['note'] ?? null,
            type: $data['type'],
            createdAt: $data['created_at'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
