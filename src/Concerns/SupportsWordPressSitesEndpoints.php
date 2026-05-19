<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Site;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\SitePayload;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CloneSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreatePlainSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreateSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\DeleteSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSitesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\ResetSiteRequest;

/** @mixin Kinsta */
trait SupportsWordPressSitesEndpoints
{
    public function sites(?bool $includeEnvironments): array
    {
        $request = new GetSitesRequest($this->companyId, $includeEnvironments);

        return $this->send($request)->dto();
    }

    public function site(string $siteId): Site
    {
        $request = new GetSiteRequest($siteId);

        return $this->send($request)->dtoOrFail();
    }

    public function createSite(SitePayload $payload): OperationResponse
    {
        $request = new CreateSiteRequest($payload, $this->companyId);

        return $this->send($request)->dto();
    }

    public function createPlainSite(string $displayName, string $region): OperationResponse
    {
        $request = new CreatePlainSiteRequest($this->companyId, $displayName, $region);

        return $this->send($request)->dto();
    }

    public function cloneSite(string $displayName, string $siteId): OperationResponse
    {
        $request = new CloneSiteRequest($this->companyId, $displayName, $siteId);

        return $this->send($request)->dto();
    }

    public function deleteSite(string $siteId): OperationResponse
    {
        $request = new DeleteSiteRequest($siteId);

        return $this->send($request)->dto();
    }

    public function resetSite(string $siteId, string $adminPassword): OperationResponse
    {
        $request = new ResetSiteRequest($siteId, $adminPassword);

        return $this->send($request)->dto();
    }
}
