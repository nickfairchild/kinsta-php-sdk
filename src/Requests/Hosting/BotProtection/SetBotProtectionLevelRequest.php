<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Enums\ProtectionLevel;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetBotProtectionLevelRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected ProtectionLevel $level,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/level";
    }

    protected function defaultBody(): array
    {
        return [
            'level' => $this->level->value,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
