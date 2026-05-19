<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\AddSiteDomainData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\SiteDomains;
use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\VerificationRecords;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\AddSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\ChangePrimaryDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\DeleteSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetSiteDomainsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetVerificationRecordsRequest;

/** @mixin Kinsta */
trait SupportsSiteDomainsEndpoints
{
    public function getSiteDomains(string $envId): SiteDomains
    {
        $request = new GetSiteDomainsRequest($envId);

        return $this->send($request)->dto();
    }

    public function addSiteDomain(string $envId, AddSiteDomainData $data): OperationResponse
    {
        $request = new AddSiteDomainRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function deleteSiteDomain(string $envId, string $domainId): OperationResponse
    {
        $request = new DeleteSiteDomainRequest($envId, $domainId);

        return $this->send($request)->dto();
    }

    public function getVerificationRecords(string $siteDomainId): VerificationRecords
    {
        $request = new GetVerificationRecordsRequest($siteDomainId);

        return $this->send($request)->dto();
    }

    public function changePrimaryDomain(string $envId, string $domainId): OperationResponse
    {
        $request = new ChangePrimaryDomainRequest($envId, $domainId);

        return $this->send($request)->dto();
    }
}
