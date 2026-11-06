<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetEdgeCacheStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected bool $enabled,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/edge-caching/status';
    }

    protected function defaultBody(): array
    {
        return [
            'environment_id' => $this->envId,
            'enabled' => $this->enabled,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
