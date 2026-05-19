<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetImageOptimizationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected bool $enabled,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/cdn/image-optimization';
    }

    protected function defaultBody(): array
    {
        return [
            'site_id' => $this->siteId,
            'enabled' => $this->enabled ? 'true' : 'false',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
