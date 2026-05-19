<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListResult;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentPhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentWebrootRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeSitePhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ClearSiteCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ListFilesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ModifyPhpVersionRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\RestartPhpEngineRequest;

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

    public function modifyPhpVersion(string $envId, string $phpVersion): OperationResponse
    {
        $request = new ModifyPhpVersionRequest($envId, $phpVersion);

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

    public function changeEnvironmentWebroot(string $envId, string $subfolder): OperationResponse
    {
        $request = new ChangeEnvironmentWebrootRequest($envId, $subfolder);

        return $this->send($request)->dto();
    }

    public function listFiles(string $envId, FileListData $data): FileListResult
    {
        $request = new ListFilesRequest($envId, $data);

        return $this->send($request)->dto();
    }
}
