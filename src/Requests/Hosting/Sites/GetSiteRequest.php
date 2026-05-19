<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Site;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSiteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public string $siteId) {}

    public function resolveEndpoint(): string
    {
        return '/sites/'.$this->siteId;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Site::fromArray($response->json('site'));
    }
}
