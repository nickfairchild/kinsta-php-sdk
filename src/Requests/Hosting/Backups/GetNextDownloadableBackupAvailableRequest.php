<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\NextDownloadableBackup;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetNextDownloadableBackupAvailableRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/next-downloadable-backup-available";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return NextDownloadableBackup::fromResponse($response->json());
    }
}
