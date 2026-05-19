<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\AddManualBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AddManualBackupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected AddManualBackupRequestData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/manual-backups";
    }

    protected function defaultBody(): array
    {
        return [
            'tag' => $this->data->tag,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
