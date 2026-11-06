<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

use Nickfairchild\KinstaPhpSdk\Enums\ProtectionLevel;

readonly class BotProtectionLevel
{
    public function __construct(
        public string $id,
        public ?ProtectionLevel $level,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            // Values outside the documented enum become null rather than throwing.
            level: ProtectionLevel::tryFrom($data['level'] ?? ''),
        );
    }
}
