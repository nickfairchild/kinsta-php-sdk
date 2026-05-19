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

    public function __construct(protected string $siteId) {}

    public function resolveEndpoint(): string
    {
        return '/sites/edge-caching/clear';
    }

    protected function defaultBody(): array
    {
        return [
            'site_id' => $this->siteId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
