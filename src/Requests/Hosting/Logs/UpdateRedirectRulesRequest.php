<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateRedirectRulesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected array $rules,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/redirect-rules";
    }

    protected function defaultBody(): array
    {
        return [
            'redirect_rules' => $this->rules,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
