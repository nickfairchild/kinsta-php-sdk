<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\BotProtectionLevel;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBotProtectionLevelRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/level";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json('bot_protection');

        return $data ? BotProtectionLevel::fromResponse($data) : null;
    }
}
