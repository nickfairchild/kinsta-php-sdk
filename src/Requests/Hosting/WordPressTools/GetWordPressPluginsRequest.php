<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WordPressPlugins;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWordPressPluginsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/wp-plugins";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return WordPressPlugins::fromResponse($response->json());
    }
}
