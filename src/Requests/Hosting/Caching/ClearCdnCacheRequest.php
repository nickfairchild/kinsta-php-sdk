<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ClearCdnCacheRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $cdnCacheId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/cdn/clear-cache';
    }

    protected function defaultBody(): array
    {
        return [
            'environment_id' => $this->envId,
            'cdn_cache_id' => $this->cdnCacheId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
