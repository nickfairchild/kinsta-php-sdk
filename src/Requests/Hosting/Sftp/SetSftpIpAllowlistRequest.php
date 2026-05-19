<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetSftpIpAllowlistRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected array $ips,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/set-allowed-ips";
    }

    protected function defaultBody(): array
    {
        return [
            'allowed_ips' => $this->ips,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
