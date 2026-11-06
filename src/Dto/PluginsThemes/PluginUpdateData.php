<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes;

readonly class PluginUpdateData
{
    public function __construct(
        public string $name,
        public string $updateVersion,
    ) {}
}
