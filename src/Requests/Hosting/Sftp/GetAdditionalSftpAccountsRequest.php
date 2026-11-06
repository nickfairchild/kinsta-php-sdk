<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpAccounts;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAdditionalSftpAccountsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/additional-sftp-accounts";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return SftpAccounts::fromResponse($response->json('environment'));
    }
}
