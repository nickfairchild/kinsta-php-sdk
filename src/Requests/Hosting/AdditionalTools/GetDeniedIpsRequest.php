<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools;

use Nickfairchild\KinstaPhpSdk\Dto\Tools\DeniedIpsResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDeniedIpsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

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

    public function createDtoFromResponse(Response $response): mixed
    {
        return DeniedIpsResult::fromResponse($response->json());
    }
}
