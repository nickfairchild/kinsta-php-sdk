<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListResult;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\ForceHttpsStatus;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\SearchAndReplaceData;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentPhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentWebrootRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeSitePhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ClearSiteCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\GetForceHttpsStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ListFilesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ModifyPhpVersionRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\RestartPhpEngineRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\SearchAndReplaceRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\SetForceHttpsStatusRequest;

/** @mixin Kinsta */
trait SupportsSiteToolsEndpoints
{
    public function clearSiteCache(string $envId): OperationResponse
    {
        $request = new ClearSiteCacheRequest($envId);

        return $this->send($request)->dto();
    }

    public function restartPhpEngine(string $envId): OperationResponse
    {
        $request = new RestartPhpEngineRequest($envId);

        return $this->send($request)->dto();
    }

    public function modifyPhpVersion(string $envId, string $phpVersion, ?bool $optOutFromAutomaticPhpUpdate = null): OperationResponse
    {
        $request = new ModifyPhpVersionRequest($envId, $phpVersion, $optOutFromAutomaticPhpUpdate);

        return $this->send($request)->dto();
    }

    public function changeSitePhpAllocation(string $siteId, PhpAllocationData $data): OperationResponse
    {
        $request = new ChangeSitePhpAllocationRequest($siteId, $data);

        return $this->send($request)->dto();
    }

    public function changeEnvironmentPhpAllocation(string $envId, PhpAllocationData $data): OperationResponse
    {
        $request = new ChangeEnvironmentPhpAllocationRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function changeEnvironmentWebroot(string $envId, string $subfolder, ?bool $clearAllCache = null, ?bool $refreshPluginsAndThemes = null): OperationResponse
    {
        $request = new ChangeEnvironmentWebrootRequest($envId, $subfolder, $clearAllCache, $refreshPluginsAndThemes);

        return $this->send($request)->dto();
    }

    public function listFiles(string $envId): FileListResult
    {
        $request = new ListFilesRequest($envId);

        return $this->send($request)->dto();
    }

    public function getForceHttpsStatus(string $envId): ForceHttpsStatus
    {
        $request = new GetForceHttpsStatusRequest($envId);

        return $this->send($request)->dto();
    }

    public function setForceHttpsStatus(string $envId, string $type): OperationResponse
    {
        $request = new SetForceHttpsStatusRequest($envId, $type);

        return $this->send($request)->dto();
    }

    public function searchAndReplace(string $envId, SearchAndReplaceData $data): OperationResponse
    {
        $request = new SearchAndReplaceRequest($envId, $data);

        return $this->send($request)->dto();
    }
}
