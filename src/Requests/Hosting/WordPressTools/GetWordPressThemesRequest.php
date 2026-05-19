<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WordPressThemes;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWordPressThemesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/wp-themes";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return WordPressThemes::fromResponse($response->json());
    }
}
