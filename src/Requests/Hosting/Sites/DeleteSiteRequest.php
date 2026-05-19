<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteSiteRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public string $siteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/'.$this->siteId;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
