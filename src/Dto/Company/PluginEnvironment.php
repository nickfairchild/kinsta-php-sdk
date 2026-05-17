<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class PluginEnvironment
{
    public function __construct(
        protected string $id,
        protected string $siteDisplayName,
        protected string $displayName,
        protected string $pluginStatus,
        protected ?string $pluginUpdate,
        protected string $pluginVersion,
        protected string $isPluginVersionVulnerable,
        protected ?string $pluginUpdateVersion,
        protected string $isPluginUpdateVersionVulnerable,
        protected ?string $pluginUpdateStatus,
        protected ?string $autoUpdateType,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            siteDisplayName: $data['site_display_name'],
            displayName: $data['display_name'],
            pluginStatus: $data['plugin_status'],
            pluginUpdate: $data['plugin_update'] ?? null,
            pluginVersion: $data['plugin_version'],
            isPluginVersionVulnerable: $data['is_plugin_version_vulnerable'],
            pluginUpdateVersion: $data['plugin_update_version'] ?? null,
            isPluginUpdateVersionVulnerable: $data['is_plugin_update_version_vulnerable'],
            pluginUpdateStatus: $data['plugin_update_status'] ?? null,
            autoUpdateType: $data['auto_update_type'] ?? null,
        );
    }

    public static function collection(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
