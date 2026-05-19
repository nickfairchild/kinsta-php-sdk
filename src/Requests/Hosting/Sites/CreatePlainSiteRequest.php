<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreatePlainSiteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $companyId,
        public string $displayName,
        public string $region,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/plain';
    }

    protected function defaultBody(): array
    {
        return [
            'company' => $this->companyId,
            'display_name' => $this->displayName,
            'region' => $this->region,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
