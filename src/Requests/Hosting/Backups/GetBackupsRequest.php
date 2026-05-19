<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\BackupEnvironment;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBackupsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/backups";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return BackupEnvironment::fromResponse($response->json('environment'));
    }
}
