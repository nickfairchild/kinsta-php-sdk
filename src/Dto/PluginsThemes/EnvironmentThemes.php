<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class EnvironmentThemes
{
    public function __construct(
        public array $themes,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            themes: $data['environment']['container_info']['wp_themes']['data'] ?? [],
        );
    }
}
