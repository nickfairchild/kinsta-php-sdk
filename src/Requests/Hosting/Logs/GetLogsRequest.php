<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetLogsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
        protected LogsQuery $logsQuery,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/logs";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'file_name' => $this->logsQuery->fileName,
            'lines' => $this->logsQuery->lines,
            'from' => $this->logsQuery->from,
            'to' => $this->logsQuery->to,
            'search' => $this->logsQuery->search,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return LogsResult::fromResponse($response->json());
    }
}
