<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ClearEdgeCacheRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected ?bool $clearSubdirectories = null,
        protected ?string $url = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/edge-caching/clear';
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'environment_id' => $this->envId,
            'clear_subdirectories' => $this->clearSubdirectories,
            'url' => $this->url,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
