<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Environment;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Environment::fromArray($response->json('environment'));
    }
}
