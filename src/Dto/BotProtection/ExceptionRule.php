<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\BotProtection;

readonly class ExceptionRule
{
    public function __construct(
        public string $id,
        public string $type,
        public string $action,
        public string $value,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            action: $data['action'],
            value: $data['value'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
