<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteSiteDomainRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $envId,
        protected string $domainId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/domains";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
