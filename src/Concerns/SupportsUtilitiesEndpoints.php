<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Utilities\GetOperationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Utilities\ValidateAPIKeyRequest;

/** mixin Kinsta */
trait SupportsUtilitiesEndpoints
{
    public function validateApiKey(): array
    {
        $request = new ValidateAPIKeyRequest;

        return $this->send($request)->json();
    }

    public function getOperation($operationId)
    {
        $request = new GetOperationRequest($operationId);

        return $this->send($request)->dtoOrFail();
    }
}
