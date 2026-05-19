<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics;

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\UsageResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBandwidthUsageRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $siteId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/usage/bandwidth/this-month";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return UsageResult::fromResponse($response->json());
    }
}
