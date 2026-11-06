<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GenerateSftpPasswordRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/generate-password";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->successful();
    }
}
