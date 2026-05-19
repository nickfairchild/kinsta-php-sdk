<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreateEnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected CreateEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments";
    }

    protected function defaultBody(): array
    {
        return [
            'display_name' => $this->data->displayName,
            'env_type' => $this->data->envType,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
