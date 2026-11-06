<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class AiCrawlersStatus
{
    public function __construct(
        public string $id,
        public string $aiCrawlersBlocking,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            aiCrawlersBlocking: $data['ai_crawlers_blocking'],
        );
    }
}
