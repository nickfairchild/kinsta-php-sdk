<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class UserCapability
{
    public function __construct(
        public string $role,
        public ?string $companyId,
        public ?string $siteId,
        public ?string $id = null,
        public ?string $type = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        // The users list returns company_id/site_id; the user-by-id endpoint returns id_company/id_site.
        return new self(
            role: $data['role'],
            companyId: $data['company_id'] ?? $data['id_company'] ?? null,
            siteId: $data['site_id'] ?? $data['id_site'] ?? null,
            id: $data['id'] ?? null,
            type: $data['type'] ?? null,
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
