<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\Domain;

readonly class Environment
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $displayName,
        protected bool $isBlocked,
        protected ?string $idEdgeCache,
        protected ?string $cdnEdgeCache,
        protected ?bool $isOptOutFromAutomaticPHPUpdates,
        protected ?string $webRoot,
        protected ?string $imageOptimizationType,
        protected bool $isAdditionalSFTPAccountsEnabled,
        protected bool $isPremium,
        protected string $wordpressVersion,
        protected array $domains,
        protected Domain $primaryDomain,
        protected array $sshConnection,
        protected array $containerInfo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            displayName: $data['display_name'],
            isBlocked: $data['is_blocked'],
            idEdgeCache: $data['id_edge_cache'] ?? null,
            cdnEdgeCache: $data['cdn_edge_cache'] ?? null,
            isOptOutFromAutomaticPHPUpdates: $data['is_opt_out_from_automatic_php_updates'] ?? null,
            webRoot: $data['web_root'] ?? '',
            imageOptimizationType: $data['image_optimization_type'] ?? '',
            isAdditionalSFTPAccountsEnabled: $data['is_additional_sftp_accounts_enabled'] ?? false,
            isPremium: $data['is_premium'],
            wordpressVersion: $data['wordpress_version'] ?? '',
            domains: $data['domains'],
            primaryDomain: Domain::fromResponse($data['primary_domain'] ?? []),
            sshConnection: $data['ssh_connection'] ?? [],
            containerInfo: $data['container_info'] ?? [],
        );
    }
}
