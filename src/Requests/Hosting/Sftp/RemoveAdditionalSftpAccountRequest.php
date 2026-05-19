<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class RemoveAdditionalSftpAccountRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected string $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/additional-sftp-accounts/{$this->accountId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
