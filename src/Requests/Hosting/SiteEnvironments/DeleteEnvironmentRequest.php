<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteEnvironmentRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
