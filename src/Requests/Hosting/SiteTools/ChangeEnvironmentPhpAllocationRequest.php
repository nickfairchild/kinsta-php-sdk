<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ChangeEnvironmentPhpAllocationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected PhpAllocationData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/change-environment-php-allocation";
    }

    protected function defaultBody(): array
    {
        return [
            'memory' => $this->data->memory,
            'max_execution_time' => $this->data->maxExecutionTime,
            'http_max_connections' => $this->data->httpMaxConnections,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
