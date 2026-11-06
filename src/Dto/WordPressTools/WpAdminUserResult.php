<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WpAdminUserResult
{
    public function __construct(
        public ?string $loginUrl = null,
        public ?bool $exists = null,
        public ?bool $created = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            loginUrl: $data['login_link'] ?? null,
            exists: $data['has_wp_admin_user'] ?? null,
            created: $data['create_wp_admin_user'] ?? null,
        );
    }
}
