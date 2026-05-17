<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class Plugin
{
    public function __construct(
        public string $name,
        public string $title,
        public string $description,
        public ?string $latestVersion,
        public bool $isLatestVersionVulnerable,
        public int $environmentCount,
        public int $updateCount,
        public array $environments,
    ) {}

    public static function fromResponse(array $data): self
    {
        $environments = PluginEnvironment::collection($data['environments']);

        return new self(
            name: $data['name'],
            title: $data['title'],
            description: $data['description'],
            latestVersion: $data['latest_version'] ?? null,
            isLatestVersionVulnerable: $data['is_latest_version_vulnerable'],
            environmentCount: $data['environment_count'],
            updateCount: $data['update_count'],
            environments: $environments,
        );
    }

    public static function collection(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
