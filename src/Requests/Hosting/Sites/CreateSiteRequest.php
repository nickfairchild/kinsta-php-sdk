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
        $body = [
            'company' => $this->companyId,
            'display_name' => $this->payload->displayName,
            'region' => $this->payload->region,
            'install_mode' => $this->payload->installMode,
            'admin_email' => $this->payload->adminEmail,
            'admin_password' => $this->payload->adminPassword,
            'admin_user' => $this->payload->adminUser,
            'site_title' => $this->payload->siteTitle,
            'wp_language' => $this->payload->wpLanguage,
            'is_subdomain_multisite' => $this->payload->isSubdomainMultisite,
            'is_multisite' => $this->payload->isMultisite,
        ];

        // Deprecated by Kinsta; only sent when explicitly enabled.
        if ($this->payload->woocommerce) {
            $body['woocommerce'] = true;
        }

        if ($this->payload->wordpressSeo) {
            $body['wordpressseo'] = true;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
