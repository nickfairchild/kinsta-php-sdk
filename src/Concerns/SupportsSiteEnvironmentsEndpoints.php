<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Environment;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\EnvironmentsCollection;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CloneEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreateEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreatePlainEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\PushEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CloneEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CreateEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CreatePlainEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\DeleteEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\GetEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\GetSiteEnvironmentsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\PushEnvironmentRequest;

/** @mixin Kinsta */
trait SupportsSiteEnvironmentsEndpoints
{
    public function getSiteEnvironments(string $siteId): EnvironmentsCollection
    {
        $request = new GetSiteEnvironmentsRequest($siteId);

        return $this->send($request)->dto();
    }

    public function getEnvironment(string $envId): Environment
    {
        $request = new GetEnvironmentRequest($envId);

        return $this->send($request)->dtoOrFail();
    }

    public function createEnvironment(string $siteId, CreateEnvironmentData $data): OperationResponse
    {
        $request = new CreateEnvironmentRequest($siteId, $data);

        return $this->send($request)->dto();
    }

    public function createPlainEnvironment(string $siteId, CreatePlainEnvironmentData $data): OperationResponse
    {
        $request = new CreatePlainEnvironmentRequest($siteId, $data);

        return $this->send($request)->dto();
    }

    public function cloneEnvironment(string $siteId, CloneEnvironmentData $data): OperationResponse
    {
        $request = new CloneEnvironmentRequest($siteId, $data);

        return $this->send($request)->dto();
    }

    public function pushEnvironment(string $siteId, PushEnvironmentData $data): OperationResponse
    {
        $request = new PushEnvironmentRequest($siteId, $data);

        return $this->send($request)->dto();
    }

    public function deleteEnvironment(string $envId): OperationResponse
    {
        $request = new DeleteEnvironmentRequest($envId);

        return $this->send($request)->dto();
    }
}
