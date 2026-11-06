<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\ForceHttpsStatus;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetForceHttpsStatusRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/force-https-status";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return ForceHttpsStatus::fromResponse($response->json());
    }
}
