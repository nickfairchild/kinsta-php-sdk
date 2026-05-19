<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\SitePayload;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateSiteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(public SitePayload $payload, public string $companyId) {}

    public function resolveEndpoint(): string
    {
        return '/sites';
    }

    protected function defaultBody(): array
    {
        return [
            'display_name' => $this->payload->displayName,
            'region' => $this->payload->region,
            'admin_email' => $this->payload->adminEmail,
            'admin_password' => $this->payload->adminPassword,
            'admin_user' => $this->payload->adminUser,
            'site_title' => $this->payload->siteTitle,
            'wp_language' => $this->payload->wpLanguage,
            'company' => $this->companyId,
            'is_subdomain_multisite' => $this->payload->isSubdomainMultisite ? 'true' : 'false',
            'is_multisite' => $this->payload->isMultisite ? 'true' : 'false',
            'woocommerce' => $this->payload->woocommerce ? 'true' : 'false',
            'wordpressseo' => $this->payload->wordpressSeo ? 'true' : 'false',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
