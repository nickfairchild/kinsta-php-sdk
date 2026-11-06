<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RemoveExceptionRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $envId,
        protected string $exceptionRuleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/exception-rules";
    }

    protected function defaultBody(): array
    {
        return [
            'exception_rule_id' => $this->exceptionRuleId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
