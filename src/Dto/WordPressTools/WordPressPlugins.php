<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WordPressPlugins
{
    public function __construct(
        public array $plugins,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            plugins: $data['wp_plugins'] ?? [],
        );
    }
}
