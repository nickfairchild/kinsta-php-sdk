<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes;

use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\EnvironmentPlugins;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentPluginsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/plugins";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return EnvironmentPlugins::fromResponse($response->json());
    }
}
