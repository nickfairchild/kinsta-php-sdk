<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics;

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetVisitsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected AnalyticsQuery $analyticsQuery,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/analytics/visits";
    }

    protected function defaultBody(): array
    {
        return [
            'interval' => $this->analyticsQuery->interval,
            'timezone' => $this->analyticsQuery->timezone ?? 'UTC',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return AnalyticsResult::fromResponse($response->json());
    }
}
