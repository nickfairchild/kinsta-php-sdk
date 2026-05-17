<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Utilities;

use Nickfairchild\KinstaPhpSdk\Dto\Operation;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetOperationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $operationId
    ) {}

    public function resolveEndpoint(): string
    {
        return '/operations/'.$this->operationId;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Operation::fromResponse($response);
    }
}
