<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\EnvironmentsCollection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSiteEnvironmentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $siteId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return EnvironmentsCollection::fromResponse($response->json('site'));
    }
}
