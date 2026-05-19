<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearCdnCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearEdgeCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetEdgeCacheStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetImageOptimizationRequest;

/** @mixin Kinsta */
trait SupportsCachingEndpoints
{
    public function clearEdgeCache(string $siteId): OperationResponse
    {
        $request = new ClearEdgeCacheRequest($siteId);

        return $this->send($request)->dto();
    }

    public function setEdgeCacheStatus(string $siteId, bool $enabled): OperationResponse
    {
        $request = new SetEdgeCacheStatusRequest($siteId, $enabled);

        return $this->send($request)->dto();
    }

    public function clearCdnCache(string $siteId): OperationResponse
    {
        $request = new ClearCdnCacheRequest($siteId);

        return $this->send($request)->dto();
    }

    public function setImageOptimization(string $siteId, bool $enabled): OperationResponse
    {
        $request = new SetImageOptimizationRequest($siteId, $enabled);

        return $this->send($request)->dto();
    }
}
