<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class BulkPluginUpdateData
{
    public function __construct(
        public array $pluginSlugs,
    ) {}
}
