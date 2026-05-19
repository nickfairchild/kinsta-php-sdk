<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\RestoreBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RestoreBackupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $targetEnvId,
        protected RestoreBackupRequestData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->targetEnvId}/backups/restore";
    }

    protected function defaultBody(): array
    {
        return [
            'backup_id' => $this->data->backupId,
            'notified_user_id' => $this->data->notifiedUserId,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
