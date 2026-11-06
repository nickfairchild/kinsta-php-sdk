<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WordPressThemes
{
    public function __construct(
        public array $themes,
        public ?string $lastUpdatedAt = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            themes: $data['environment']['themes']['items'] ?? [],
            lastUpdatedAt: $data['environment']['themes']['last_updated_at'] ?? null,
        );
    }
}
