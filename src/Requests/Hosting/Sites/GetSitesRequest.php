<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Site;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSitesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $companyId,
        public bool $includeEnvironments,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites';
    }

    protected function defaultQuery(): array
    {
        return [
            'company' => $this->companyId,
            'include_environments' => $this->includeEnvironments ? 'true' : 'false',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $data) {
            return Site::fromArray($data);
        }, $response->json('company.sites'));
    }
}
