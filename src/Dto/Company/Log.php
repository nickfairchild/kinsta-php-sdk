<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class Log
{
    public function __construct(
        public int $id,
        public ?string $siteId,
        public int $createdAt,
        public bool $isDone,
        public bool $hasFailed,
        public bool $hasWarning,
        public string $type,
        public ?string $publicError,
        public ?string $ipAddress,
        public ?array $descriptions,
        public array $initiatedBy,
        public array $userAgentInfo,
        public array $apiKeyInfo,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            siteId: $data['site_id'] ?? null,
            createdAt: $data['created_at'],
            isDone: $data['is_done'],
            hasFailed: $data['has_failed'],
            hasWarning: $data['has_warning'],
            type: $data['type'],
            publicError: $data['public_error'] ?? null,
            ipAddress: $data['ip_address'] ?? null,
            descriptions: $data['descriptions'] ?? null,
            initiatedBy: $data['initiated_by'],
            userAgentInfo: $data['user_agent_info'],
            apiKeyInfo: $data['api_key_info'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
