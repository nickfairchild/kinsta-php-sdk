<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CloneEnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CloneEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected CloneEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments/clone";
    }

    protected function defaultBody(): array
    {
        return [
            'display_name' => $this->data->displayName,
            'source_env_id' => $this->data->sourceEnvId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
