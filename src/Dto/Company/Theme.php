<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class Theme
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
        return new self(
            name: $data['name'],
            title: $data['title'],
            description: $data['description'],
            latestVersion: $data['latest_version'],
            isLatestVersionVulnerable: $data['is_latest_version_vulnerable'],
            environmentCount: $data['environment_count'],
            updateCount: $data['update_count'],
            environments: ThemeEnvironment::collection($data['environments']),
        );
    }

    public static function collection(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
