<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpConfig;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSftpConfigRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $envId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/{$this->siteId}/environments/{$this->envId}/ssh/config";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return SftpConfig::fromResponse($response->json());
    }
}
