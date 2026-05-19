<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

readonly class Site
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayName,
        public string $status,
        public ?array $environments = [],
        public ?array $siteLabels = null,
        public ?string $companyId = null,
    ) {}

    public static function fromArray($data): self
    {
        $environments = array_map(function (array $data) {
            return Environment::fromArray($data);
        }, $data['environments']);

        return new self(
            id: $data['id'],
            name: $data['name'],
            displayName: $data['display_name'],
            status: $data['status'],
            environments: $environments,
            siteLabels: $data['site_labels'] ?? null,
            companyId: $data['company_id'] ?? null,
        );
    }
}
