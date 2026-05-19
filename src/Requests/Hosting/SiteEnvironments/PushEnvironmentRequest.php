<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\PushEnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class PushEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected PushEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments";
    }

    protected function defaultBody(): array
    {
        return [
            'source_env_id' => $this->data->sourceEnvId,
            'target_env_id' => $this->data->targetEnvId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
