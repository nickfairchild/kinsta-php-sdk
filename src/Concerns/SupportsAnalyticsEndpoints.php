<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\UsageResult;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBandwidthRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetBandwidthUsageRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetCdnBandwidthRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetCdnBandwidthUsageRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetDiskSpaceRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetResponseCodeBreakdownRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopCitiesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopClientIpsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Analytics\GetTopCountriesRequest;
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

    public function getCdnBandwidth(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetCdnBandwidthRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getVisits(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetVisitsRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getBandwidth(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetBandwidthRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getDiskSpace(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetDiskSpaceRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getTopCountries(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetTopCountriesRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getTopCities(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetTopCitiesRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getTopClientIps(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetTopClientIpsRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getVisitsDispersion(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetVisitsDispersionRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getResponseCodeBreakdown(string $envId, AnalyticsQuery $query): AnalyticsResult
    {
        $request = new GetResponseCodeBreakdownRequest($envId, $query);

        return $this->send($request)->dto();
    }
}
