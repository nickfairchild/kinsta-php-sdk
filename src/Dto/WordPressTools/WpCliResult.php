<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WpCliResult
{
    public function __construct(
        public string $output,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            output: $data['output'] ?? '',
        );
    }
}
