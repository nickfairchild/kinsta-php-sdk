<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

readonly class Site
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayName,
        public string $status,
        public ?EnvironmentsCollection $environments = null,
        public ?array $siteLabels = null,
        public ?string $companyId = null,
    ) {}

    public static function fromResponse($data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            displayName: $data['display_name'],
            status: $data['status'],
            environments: EnvironmentsCollection::fromResponse($data ?? []),
            siteLabels: $data['siteLabels'] ?? $data['site_labels'] ?? null,
            companyId: $data['company_id'] ?? null,
        );
    }

    public static function collect(array $items)
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
