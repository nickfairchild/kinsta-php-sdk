<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreateEnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected CreateEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments";
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'display_name' => $this->data->displayName,
            'site_title' => $this->data->siteTitle,
            'is_premium' => $this->data->isPremium,
            'admin_email' => $this->data->adminEmail,
            'admin_password' => $this->data->adminPassword,
            'admin_user' => $this->data->adminUser,
            'wp_language' => $this->data->wpLanguage,
            'is_subdomain_multisite' => $this->data->isSubdomainMultisite,
            'is_multisite' => $this->data->isMultisite,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
