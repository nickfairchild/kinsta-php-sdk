<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRules;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetRedirectRulesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/redirect-rules";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return RedirectRules::fromResponse($response->json());
    }
}
