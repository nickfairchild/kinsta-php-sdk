<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RestartPhpEngineRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return '/sites/tools/restart-php';
    }

    protected function defaultBody(): array
    {
        return [
            'environment_id' => $this->envId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
