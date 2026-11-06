<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Enums\ImageOptimizationType;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetImageOptimizationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected ImageOptimizationType $type,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/cdn/image-optimization';
    }

    protected function defaultBody(): array
    {
        return [
            'environment_id' => $this->envId,
            'image_optimization_type' => $this->type->value,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
