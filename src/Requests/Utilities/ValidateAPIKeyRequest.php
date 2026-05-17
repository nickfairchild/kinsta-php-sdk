<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Utilities;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ValidateAPIKeyRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/validate';
    }
}
