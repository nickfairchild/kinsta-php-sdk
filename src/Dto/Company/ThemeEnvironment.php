<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class ThemeEnvironment
{
    public function __construct(
        protected string $id,
        protected string $siteDisplayName,
        protected string $displayName,
        protected string $themeStatus,
        protected ?string $themeUpdate,
        protected string $themeVersion,
        protected string $isThemeVersionVulnerable,
        protected ?string $themeUpdateVersion,
        protected string $isThemeUpdateVersionVulnerable,
        protected ?string $themeUpdateStatus,
        protected ?string $autoUpdateType,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            siteDisplayName: $data['site_display_name'],
            displayName: $data['display_name'],
            themeStatus: $data['theme_status'],
            themeUpdate: $data['theme_update'] ?? null,
            themeVersion: $data['theme_version'],
            isThemeVersionVulnerable: $data['is_theme_version_vulnerable'],
            themeUpdateVersion: $data['theme_update_version'] ?? null,
            isThemeUpdateVersionVulnerable: $data['is_theme_update_version_vulnerable'],
            themeUpdateStatus: $data['theme_update_status'] ?? null,
            autoUpdateType: $data['auto_update_type'] ?? null,
        );
    }

    public static function collection(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
