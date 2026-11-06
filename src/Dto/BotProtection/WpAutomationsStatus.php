<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class WpAutomationsStatus
{
    public function __construct(
        public string $id,
        public string $wpAutomations,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            wpAutomations: $data['wp_automations'],
        );
    }
}
