<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\AddSiteDomainData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AddSiteDomainRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected AddSiteDomainData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/domains";
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'domain_name' => $this->data->domain,
            'is_wildcardless' => $this->data->isWildcardless,
            'add_with_www_subdomain' => $this->data->addWithWwwSubdomain,
            'setup_type' => $this->data->setupType,
            'custom_ssl_key' => $this->data->customSslKey,
            'custom_ssl_cert' => $this->data->customSslCert,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
