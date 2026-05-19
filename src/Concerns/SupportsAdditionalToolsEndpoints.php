<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\Tools\DeniedIpsResult;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\GetDeniedIpsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\UpdateDeniedIpsRequest;

/** @mixin Kinsta */
trait SupportsAdditionalToolsEndpoints
{
    public function getDeniedIps(string $envId): DeniedIpsResult
    {
        $request = new GetDeniedIpsRequest($envId);

        return $this->send($request)->dto();
    }

    public function updateDeniedIps(string $envId, array $ips): OperationResponse
    {
        $request = new UpdateDeniedIpsRequest($envId, $ips);

        return $this->send($request)->dto();
    }
}
