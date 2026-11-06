<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ChangeSftpPasswordExpirationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $interval,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/change-expiration-interval";
    }

    protected function defaultBody(): array
    {
        return [
            'exp_interval' => $this->interval,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->successful();
    }
}
