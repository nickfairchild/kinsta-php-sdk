<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class Region
{
    public function __construct(
        public string $name,
        public string $region,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            name: $data['name'],
            region: $data['region'],
        );
    }

    public static function collect(array $data): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $data);
    }
}
