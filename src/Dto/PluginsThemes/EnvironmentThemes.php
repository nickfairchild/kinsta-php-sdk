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
            themes: $data['themes'] ?? [],
        );
    }
}
