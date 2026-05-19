<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ToggleSftpAccessRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected bool $enabled,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/set-status";
    }

    protected function defaultBody(): array
    {
        return [
            'enabled' => $this->enabled ? 'true' : 'false',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
