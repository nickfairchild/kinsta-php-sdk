<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CloneSiteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $companyId,
        public string $displayName,
        public string $siteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/clone';
    }

    protected function defaultBody(): array
    {
        return [
            'company' => $this->companyId,
            'display_name' => $this->displayName,
            'source_env_id' => $this->siteId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
