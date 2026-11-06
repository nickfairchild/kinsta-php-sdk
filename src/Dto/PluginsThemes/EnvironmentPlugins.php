<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class EnvironmentPlugins
{
    public function __construct(
        public array $plugins,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            plugins: $data['environment']['container_info']['wp_plugins']['data'] ?? [],
        );
    }
}
