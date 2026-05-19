<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ChangeEnvironmentWebrootRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $subfolder,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/change-webroot-subfolder";
    }

    protected function defaultBody(): array
    {
        return [
            'site_subfolder_path' => $this->subfolder,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
