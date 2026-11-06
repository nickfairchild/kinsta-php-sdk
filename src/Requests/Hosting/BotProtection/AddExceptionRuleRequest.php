<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRuleData;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AddExceptionRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected ExceptionRuleData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/exception-rules";
    }

    protected function defaultBody(): array
    {
        return [
            'type' => $this->data->type,
            'action' => $this->data->action,
            'value' => $this->data->value,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
