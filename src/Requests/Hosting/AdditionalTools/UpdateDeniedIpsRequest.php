<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDeniedIpsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected array $ips,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/tools/denied-ips';
    }

    protected function defaultQuery(): array
    {
        return [
            'environment_id' => $this->envId,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'denied_ips' => $this->ips,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
