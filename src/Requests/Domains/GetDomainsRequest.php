<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Domains;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\Domain;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDomainsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $companyId) {}

    public function resolveEndpoint(): string
    {
        return '/domains';
    }

    protected function defaultQuery(): array
    {
        return [
            'company' => $this->companyId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Domain::collect($response->json('company.domains'));
    }
}
