<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

readonly class CompanyUser
{
    public function __construct(
        public string $id,
        public string $email,
        public string $image,
        public string $fullName,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['user']['id'],
            email: $data['user']['email'],
            image: $data['user']['image'],
            fullName: $data['user']['full_name'],
        );
    }

    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
