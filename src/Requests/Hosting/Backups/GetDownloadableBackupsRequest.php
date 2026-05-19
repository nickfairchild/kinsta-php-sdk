<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\DownloadableBackupEnvironment;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDownloadableBackupsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/downloadable-backups";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return DownloadableBackupEnvironment::fromResponse($response->json('environment'));
    }
}
