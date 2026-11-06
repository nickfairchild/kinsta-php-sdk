<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Enums\ImageOptimizationType;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearCdnCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearEdgeCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetEdgeCacheStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetImageOptimizationRequest;

/** @mixin Kinsta */
trait SupportsCachingEndpoints
{
    public function clearEdgeCache(string $envId, ?bool $clearSubdirectories = null, ?string $url = null): OperationResponse
    {
        $request = new ClearEdgeCacheRequest($envId, $clearSubdirectories, $url);

        return $this->send($request)->dto();
    }

    public function setEdgeCacheStatus(string $envId, bool $enabled): OperationResponse
    {
        $request = new SetEdgeCacheStatusRequest($envId, $enabled);

        return $this->send($request)->dto();
    }

    public function clearCdnCache(string $envId, string $cdnCacheId): OperationResponse
    {
        $request = new ClearCdnCacheRequest($envId, $cdnCacheId);

        return $this->send($request)->dto();
    }

    public function setImageOptimization(string $envId, ImageOptimizationType $type): OperationResponse
    {
        $request = new SetImageOptimizationRequest($envId, $type);

        return $this->send($request)->dto();
    }
}
