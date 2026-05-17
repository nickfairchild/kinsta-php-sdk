<?php

namespace Nickfairchild\KinstaPhpSdk\Dto;

use Saloon\Http\Response;

readonly class OperationResponse
{
    public function __construct(
        public string $message,
        public int $status,
        public string $operationId,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            message: $data['message'],
            status: $data['status'],
            operationId: $data['operation_id'],
        );
    }
}
