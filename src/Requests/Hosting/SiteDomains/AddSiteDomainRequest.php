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
        $body = [
            'name' => $this->data->domain,
        ];

        if ($this->data->isPrimary !== null) {
            $body['is_primary'] = $this->data->isPrimary ? 'true' : 'false';
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
