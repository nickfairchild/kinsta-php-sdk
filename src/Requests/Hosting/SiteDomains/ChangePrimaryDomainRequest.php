<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ChangePrimaryDomainRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected string $domainId,
        protected bool $runSearchAndReplace = true,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/change-primary-domain";
    }

    protected function defaultBody(): array
    {
        return [
            'domain_id' => $this->domainId,
            'run_search_and_replace' => $this->runSearchAndReplace,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
