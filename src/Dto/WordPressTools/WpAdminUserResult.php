<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WpAdminUserResult
{
    public function __construct(
        public ?string $loginUrl = null,
        public ?bool $exists = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            loginUrl: $data['login_url'] ?? null,
            exists: $data['exists'] ?? null,
        );
    }
}
