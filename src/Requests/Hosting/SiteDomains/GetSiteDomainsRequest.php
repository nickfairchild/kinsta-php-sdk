<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains;

use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\SiteDomains;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSiteDomainsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/domains";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return SiteDomains::fromResponse($response->json());
    }
}
