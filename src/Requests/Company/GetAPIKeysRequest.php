<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\APIKey;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAPIKeysRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $companyId) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/api-keys';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return APIKey::collect($response->json('api_keys'));
    }
}
