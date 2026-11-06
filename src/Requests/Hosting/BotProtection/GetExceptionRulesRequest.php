<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRules;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetExceptionRulesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/exception-rules";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json('bot_protection');

        return $data ? ExceptionRules::fromResponse($data) : null;
    }
}
