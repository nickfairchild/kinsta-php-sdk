<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\Domain;

readonly class Environment
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayName,
        public bool $isBlocked,
        public ?string $idEdgeCache,
        public ?string $cdnEdgeCache,
        public ?bool $isOptOutFromAutomaticPHPUpdates,
        public ?string $webRoot,
        public ?string $imageOptimizationType,
        public bool $isAdditionalSFTPAccountsEnabled,
        public bool $isPremium,
        public string $wordpressVersion,
        public array $domains,
        public Domain $primaryDomain,
        public array $sshConnection,
        public array $containerInfo,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            displayName: $data['display_name'],
            isBlocked: $data['is_blocked'],
            idEdgeCache: $data['id_edge_cache'] ?? null,
            cdnEdgeCache: $data['cdn_cache_id'] ?? $data['cdn_edge_cache'] ?? null,
            isOptOutFromAutomaticPHPUpdates: $data['is_opt_out_from_automatic_php_update'] ?? $data['is_opt_out_from_automatic_php_updates'] ?? null,
            webRoot: $data['web_root'] ?? '',
            imageOptimizationType: $data['image_optimization_type'] ?? '',
            isAdditionalSFTPAccountsEnabled: $data['is_additional_sftp_accounts_enabled'] ?? false,
            isPremium: $data['is_premium'],
            wordpressVersion: $data['wordpress_version'] ?? '',
            domains: $data['domains'],
            primaryDomain: Domain::fromResponse($data['primaryDomain'] ?? $data['primary_domain'] ?? []),
            sshConnection: $data['ssh_connection'] ?? [],
            containerInfo: $data['container_info'] ?? [],
        );
    }
}
