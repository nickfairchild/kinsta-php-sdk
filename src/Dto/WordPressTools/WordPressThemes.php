<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WordPressThemes
{
    public function __construct(
        public array $themes,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            themes: $data['wp_themes'] ?? [],
        );
    }
}
