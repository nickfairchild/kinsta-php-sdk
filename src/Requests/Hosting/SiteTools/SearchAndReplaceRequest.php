<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\SearchAndReplaceData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchAndReplaceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected SearchAndReplaceData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/tools/search-and-replace';
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'environment_id' => $this->envId,
            'search' => $this->data->search,
            'replace' => $this->data->replace,
            'perform_replacement' => $this->data->performReplacement,
            'is_clear_cache' => $this->data->isClearCache,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
