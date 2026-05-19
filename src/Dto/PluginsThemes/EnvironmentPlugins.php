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
            plugins: $data['plugins'] ?? [],
        );
    }
}
