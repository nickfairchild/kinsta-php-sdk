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

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $domainId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/change-primary-domain";
    }

    protected function defaultBody(): array
    {
        return [
            'site_domain_id' => $this->domainId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
