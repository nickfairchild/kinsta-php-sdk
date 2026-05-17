<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\Region;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetRegionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $companyId) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/available-regions';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Region::collect($response->json('company.available_regions'));
    }
}
