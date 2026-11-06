<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetAiCrawlersBlockingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected string $status,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/bot-protection/ai-crawlers";
    }

    protected function defaultBody(): array
    {
        return [
            'status' => $this->status,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
