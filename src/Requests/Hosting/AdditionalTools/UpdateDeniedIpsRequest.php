<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDeniedIpsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected array $ips,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/tools/denied-ips';
    }

    protected function defaultBody(): array
    {
        return [
            'environment_id' => $this->envId,
            'ip_list' => $this->ips,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->successful();
    }
}
