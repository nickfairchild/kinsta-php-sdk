<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class RemoveBackupRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected int $backupId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/backups/{$this->backupId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
