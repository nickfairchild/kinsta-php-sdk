<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Nickfairchild\KinstaPhpSdk\Dto\Company\Theme;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetCompanyThemesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::GET;

    public function __construct(protected readonly string $companyId, protected readonly PluginThemePayload $payload) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/wp-themes';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json('company.themes');

        return [
            'total' => $data['total'],
            'last_updated_at' => $data['last_updated_at'],
            'items' => Theme::collection($data['items']),
        ];
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->body()->set([
            'offset' => $this->payload->offset,
            'limit' => $this->payload->limit,
            'search' => $this->payload->search,
            'status' => $this->payload->status,
            'column' => $this->payload->column,
            'order_by' => $this->payload->orderBy,
        ]);
    }
}
