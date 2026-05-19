<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups;

readonly class DownloadableBackup
{
    public function __construct(
        public string $id,
        public int $createdAt,
        public ?string $downloadLink,
        public int $expiresAt,
        public bool $isGenerationInProgress,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            createdAt: $data['created_at'],
            downloadLink: $data['download_link'] ?? null,
            expiresAt: $data['expires_at'],
            isGenerationInProgress: $data['is_generation_in_progress'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
