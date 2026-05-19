<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class ThemeUpdateData
{
    public function __construct(
        public string $slug,
        public string $version,
    ) {}
}
