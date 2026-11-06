<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\BotProtectionAnalyticsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\UsageResult;
use Nickfairchild\KinstaPhpSdk\Kinsta;
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

/** @mixin Kinsta */
trait SupportsAnalyticsEndpoints
{
    public function getVisitsUsage(string $siteId): UsageResult
    {
        $request = new GetVisitsUsageRequest($siteId);

        return $this->send($request)->dto();
    }

    public function getBandwidthUsage(string $siteId): UsageResult
    {
        $request = new GetBandwidthUsageRequest($siteId);

        return $this->send($request)->dto();
    }

    public function getCdnBandwidthUsage(string $siteId): UsageResult
    {
        $request = new GetCdnBandwidthUsageRequest($siteId);

        return $this->send($request)->dto();
    }

    public function getCdnBandwidth(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetCdnBandwidthRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getVisits(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetVisitsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getBandwidth(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetBandwidthRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getDiskSpace(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetDiskSpaceRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopCountries(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopCountriesRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopCities(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopCitiesRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopClientIps(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopClientIpsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getVisitsDispersion(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetVisitsDispersionRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getResponseCodeBreakdown(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetResponseCodeBreakdownRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopAsns(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopAsnsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopBrowsers(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopBrowsersRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopHosts(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopHostsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopReferrers(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopReferrersRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getTopUserAgents(string $envId, AnalyticsQuery $query = new AnalyticsQuery): AnalyticsResult
    {
        $request = new GetTopUserAgentsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getBotTrafficFlow(string $envId, AnalyticsQuery $query = new AnalyticsQuery): BotProtectionAnalyticsResult
    {
        $request = new GetBotTrafficFlowRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getBotTrafficRequests(string $envId, AnalyticsQuery $query = new AnalyticsQuery): BotProtectionAnalyticsResult
    {
        $request = new GetBotTrafficRequestsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getBotProtectionResults(string $envId, AnalyticsQuery $query = new AnalyticsQuery): BotProtectionAnalyticsResult
    {
        $request = new GetBotProtectionResultsRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }

    public function getBotTopBreakdown(string $envId, AnalyticsQuery $query = new AnalyticsQuery): BotProtectionAnalyticsResult
    {
        $request = new GetBotTopBreakdownRequest($envId, $this->companyId, $query);

        return $this->send($request)->dto();
    }
}
