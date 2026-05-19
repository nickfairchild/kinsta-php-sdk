<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes;

use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\EnvironmentThemes;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentThemesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/themes";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return EnvironmentThemes::fromResponse($response->json());
    }
}
