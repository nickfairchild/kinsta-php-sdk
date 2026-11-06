<?php

namespace Nickfairchild\KinstaPhpSdk\Dto;

use Saloon\Http\Response;

readonly class OperationResponse
{
    public function __construct(
        public string $message,
        public int $status,
        public ?string $operationId = null,
        public ?int $supportReferenceId = null,
        public ?string $warning = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            message: $data['message'],
            status: $data['status'],
            operationId: $data['operation_id'] ?? null,
            supportReferenceId: $data['support_reference_id'] ?? null,
            warning: $data['warning'] ?? null,
        );
    }
}
