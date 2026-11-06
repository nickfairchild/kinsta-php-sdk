<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class BotProtectionLevel
{
    public function __construct(
        public string $id,
        public string $level,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            level: $data['level'],
        );
    }
}
