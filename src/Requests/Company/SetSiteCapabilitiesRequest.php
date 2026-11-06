<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\SiteCapabilityData;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetSiteCapabilitiesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string[]  $userIds
     * @param  SiteCapabilityData[]  $siteCapabilities
     */
    public function __construct(
        protected string $companyId,
        protected array $userIds,
        protected array $siteCapabilities,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/users/site-capabilities';
    }

    protected function defaultBody(): array
    {
        return [
            'user_ids' => $this->userIds,
            'site_capabilities' => array_map(fn (SiteCapabilityData $capability) => [
                'site_id' => $capability->siteId,
                'role' => $capability->role,
            ], $this->siteCapabilities),
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
