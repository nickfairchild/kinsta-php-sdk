<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetLogsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected LogsQuery $logsQuery,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/logs";
    }

    protected function defaultBody(): array
    {
        return [
            'log_type' => $this->logsQuery->logType,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return LogsResult::fromResponse($response->json());
    }
}
