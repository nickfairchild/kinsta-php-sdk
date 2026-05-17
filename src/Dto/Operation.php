<?php

namespace Nickfairchild\KinstaPhpSdk\Dto;

use Saloon\Http\Response;

readonly class Operation
{
    public function __construct(
        public string $message,
        public int $status,
        public mixed $data,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            message: $data['message'],
            status: $data['status'],
            data: $data['data'],
        );
    }
}
