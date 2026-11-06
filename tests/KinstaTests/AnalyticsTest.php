<?php

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\BotProtectionAnalyticsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\UsageResult;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBandwidthRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBandwidthUsageRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBotProtectionResultsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBotTopBreakdownRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBotTrafficFlowRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBotTrafficRequestsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetCdnBandwidthRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetCdnBandwidthUsageRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetDiskSpaceRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetResponseCodeBreakdownRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopAsnsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopBrowsersRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopCitiesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopClientIpsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopCountriesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopHostsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopReferrersRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopUserAgentsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetVisitsDispersionRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetVisitsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetVisitsUsageRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get this month usage', function (string $method, string $requestClass, string $path, array $site, int $expected) {
    $mock = MockClient::global([
        $requestClass => MockResponse::make(['site' => $site]),
    ]);

    $usage = $this->kinsta->{$method}('site-123');

    expect($usage)->toBeInstanceOf(UsageResult::class)
        ->and($usage->siteId)->toBe('site-123')
        ->and($usage->value)->toBe($expected);

    $mock->assertSent(fn ($request, $response) => $request->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === "https://api.kinsta.com/v2/sites/site-123/usage/{$path}/this-month");
})->with([
    ['getVisitsUsage', GetVisitsUsageRequest::class, 'visits', ['id' => 'site-123', 'this_month_usage' => ['visits' => 120]], 120],
    ['getBandwidthUsage', GetBandwidthUsageRequest::class, 'bandwidth', ['id' => 'site-123', 'this_month_usage' => ['bandwidth' => 2048]], 2048],
    ['getCdnBandwidthUsage', GetCdnBandwidthUsageRequest::class, 'cdn-bandwidth', ['id' => 'site-123', 'this_month_cdn_usage' => ['bandwidth' => 512]], 512],
]);

it('can get environment analytics', function (string $method, string $requestClass, string $path) {
    $mock = MockClient::global([
        $requestClass => MockResponse::make([
            'analytics' => [
                'analytics_response' => [
                    'key' => $path,
                    'data' => [['name' => 'x', 'total' => 10, 'dataset' => [['key' => '2025-10-28 00:00:00 +0000', 'value' => '10']]]],
                ],
            ],
        ], 202),
    ]);

    $result = $this->kinsta->{$method}('env-123', new AnalyticsQuery(timeSpan: '7_days', from: '2025-10-01'));

    expect($result)->toBeInstanceOf(AnalyticsResult::class)
        ->and($result->key)->toBe($path)
        ->and($result->data)->toHaveCount(1);

    $mock->assertSent(function ($request, $response) use ($path) {
        $pending = $response->getPendingRequest();

        return $request->getMethod() === Method::GET
            && $pending->getUrl() === "https://api.kinsta.com/v2/sites/environments/env-123/analytics/{$path}"
            && $pending->query()->all() === ['company_id' => 'company-id', 'time_span' => '7_days', 'from' => '2025-10-01'];
    });
})->with([
    ['getVisits', GetVisitsRequest::class, 'visits'],
    ['getBandwidth', GetBandwidthRequest::class, 'bandwidth'],
    ['getCdnBandwidth', GetCdnBandwidthRequest::class, 'cdn-bandwidth'],
    ['getTopCountries', GetTopCountriesRequest::class, 'top-countries'],
    ['getTopCities', GetTopCitiesRequest::class, 'top-cities'],
    ['getTopClientIps', GetTopClientIpsRequest::class, 'top-client-ips'],
    ['getVisitsDispersion', GetVisitsDispersionRequest::class, 'visits-dispersion'],
    ['getResponseCodeBreakdown', GetResponseCodeBreakdownRequest::class, 'response-codes'],
    ['getTopAsns', GetTopAsnsRequest::class, 'top-asns'],
    ['getTopBrowsers', GetTopBrowsersRequest::class, 'top-browsers'],
    ['getTopHosts', GetTopHostsRequest::class, 'top-hosts'],
    ['getTopReferrers', GetTopReferrersRequest::class, 'top-referrers'],
    ['getTopUserAgents', GetTopUserAgentsRequest::class, 'top-uas'],
]);

it('sends only company_id when no analytics query is given', function () {
    $mock = MockClient::global([
        GetVisitsRequest::class => MockResponse::make(['analytics' => ['analytics_response' => ['key' => 'visits', 'data' => []]]], 202),
    ]);

    $this->kinsta->getVisits('env-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->query()->all() === ['company_id' => 'company-id']);
});

it('sends the required time_zone for disk space', function () {
    $mock = MockClient::global([
        GetDiskSpaceRequest::class => MockResponse::make(['analytics' => ['analytics_response' => ['key' => 'diskspace', 'data' => []]]], 202),
    ]);

    $this->kinsta->getDiskSpace('env-123', new AnalyticsQuery(timeSpan: '30_days', timeZone: '-05:00'));
    $this->kinsta->getDiskSpace('env-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/analytics/diskspace'
        && $response->getPendingRequest()->query()->all() === ['company_id' => 'company-id', 'time_span' => '30_days', 'time_zone' => '-05:00']);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->query()->all() === ['company_id' => 'company-id', 'time_zone' => '00:00']);
});

it('can get bot protection analytics', function (string $method, string $requestClass, string $path, string $key) {
    $payload = [['traffic_type' => 'ai_crawlers', 'label' => 'AI crawlers', 'total' => 5]];

    $mock = MockClient::global([
        $requestClass => MockResponse::make(['analytics' => [$key => $payload]]),
    ]);

    $result = $this->kinsta->{$method}('env-123', new AnalyticsQuery(timeSpan: '24_hours'));

    expect($result)->toBeInstanceOf(BotProtectionAnalyticsResult::class)
        ->and($result->data)->toBe($payload);

    $mock->assertSent(fn ($request, $response) => $request->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === "https://api.kinsta.com/v2/sites/environments/env-123/analytics/bot-protection/{$path}"
        && $response->getPendingRequest()->query()->all() === ['company_id' => 'company-id', 'time_span' => '24_hours']);
})->with([
    ['getBotTrafficFlow', GetBotTrafficFlowRequest::class, 'traffic-flow', 'traffic_flow'],
    ['getBotTrafficRequests', GetBotTrafficRequestsRequest::class, 'requests', 'requests_over_time'],
    ['getBotProtectionResults', GetBotProtectionResultsRequest::class, 'protection-results', 'protection_results_over_time'],
    ['getBotTopBreakdown', GetBotTopBreakdownRequest::class, 'top-breakdown', 'top_requests_breakdown'],
]);
