<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WordPressPlugins
{
    public function __construct(
        public array $plugins,
        public ?string $lastUpdatedAt = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            plugins: $data['environment']['plugins']['items'] ?? [],
            lastUpdatedAt: $data['environment']['plugins']['last_updated_at'] ?? null,
        );
    }
}
