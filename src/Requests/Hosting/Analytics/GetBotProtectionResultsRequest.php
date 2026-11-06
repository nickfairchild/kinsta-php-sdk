<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics;

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\BotProtectionAnalyticsResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBotProtectionResultsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
        protected string $companyId,
        protected AnalyticsQuery $analyticsQuery = new AnalyticsQuery,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/analytics/bot-protection/protection-results";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'company_id' => $this->companyId,
            'time_span' => $this->analyticsQuery->timeSpan,
            'from' => $this->analyticsQuery->from,
            'to' => $this->analyticsQuery->to,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return BotProtectionAnalyticsResult::fromResponse($response->json('analytics.protection_results_over_time') ?? []);
    }
}
