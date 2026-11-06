<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

readonly class EnvironmentsCollection
{
    public function __construct(
        public array $environments,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            environments: array_map(
                fn (array $env) => Environment::fromResponse($env),
                $data['environments'] ?? [],
            ),
        );
    }
}
