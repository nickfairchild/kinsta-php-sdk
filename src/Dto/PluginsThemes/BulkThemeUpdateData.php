<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class BulkThemeUpdateData
{
    public function __construct(
        public array $themeSlugs,
    ) {}
}
