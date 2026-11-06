<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class PhpMyAdminLink
{
    public function __construct(
        public string $url,
        public int $expiresAt,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            url: $data['url'],
            expiresAt: $data['expires_at'],
        );
    }
}
