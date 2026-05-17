<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class ActivityLogs
{
    public function __construct(
        public array $items,
        public int $total,
    ) {}

    public static function fromResponse(array $data): self
    {
        $items = Log::collect($data['items']);

        return new self(
            items: $items,
            total: $data['total'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
