<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ToggleSftpPasswordAccessRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected bool $enabled,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/set-password-status";
    }

    protected function defaultBody(): array
    {
        return [
            'is_enabled' => $this->enabled,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->successful();
    }
}
