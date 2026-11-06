<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpStatus;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSshStatusRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/get-status";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return SftpStatus::fromResponse($response->json('environment.active_container'));
    }
}
