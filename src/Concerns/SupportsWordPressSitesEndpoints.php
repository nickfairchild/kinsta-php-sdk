<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Site;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSitesRequest;

/** mixin Kinsta */
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
}
